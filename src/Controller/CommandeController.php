<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\StatutHistorique;
use App\Form\CommandeFormType;
use App\Repository\MenuRepository;
use App\Repository\HoraireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use App\Document\StatistiqueCommande;
use Doctrine\ODM\MongoDB\DocumentManager;

class CommandeController extends AbstractController
{
    #[Route('/commande/nouvelle/{menuId}', name: 'app_commande_nouvelle')]
    public function nouvelle(int $menuId, MenuRepository $menuRepository, Request $request, HoraireRepository $horaireRepository): Response
    {
        // Vérification de la connexion utilisateur avec information et redirection vers la page de connexion, le cas échéant
        if (!$this->getUser()) {
            $this->addFlash('info', 'Veuillez vous connecter ou créer un compte pour passer votre commande.');
            $request->getSession()->set('_security.main.target_path', $this->generateUrl('app_commande_nouvelle', ['menuId' => $menuId]));

            return $this->redirectToRoute('app_login');
        }

        $menu = $menuRepository->find($menuId);

        if (!$menu) {
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeFormType::class, $commande, [
                'nb_personnes_min' => $menu->getNbPersonnesMin(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le nombre de personnes ne peut pas être inférieur au minimum du menu
            if ($commande->getNbPersonnes() < $menu->getNbPersonnesMin()) {
                $this->addFlash('error', sprintf(
                    'Ce menu doit concerner au moins %d personnes.',
                    $menu->getNbPersonnesMin()
                ));

                return $this->redirectToRoute('app_commande_nouvelle', ['menuId' => $menuId]);
            }

            // Le stock disponible doit être vérifié avant toute création
            if ($menu->getStockDisponible() <= 0) {
                $this->addFlash('error', 'Ce menu n\'est pas disponible pour le moment.');

                return $this->redirectToRoute('app_menu_detail', ['id' => $menuId]);
            }

            // Le délai minimum de commande doit être respecté
            $dateLimite = new \DateTime();
            $dateLimite->modify('+' . $menu->getDelaiMinimumJours() . ' days');

            if ($commande->getDatePrestation() < $dateLimite) {
                $this->addFlash('error', sprintf(
                    'Ce menu doit être commandé au moins %d jours avant la date de prestation.',
                    $menu->getDelaiMinimumJours()
                ));

                return $this->redirectToRoute('app_commande_nouvelle', ['menuId' => $menuId]);
            }

            // L'heure de livraison doit être comprise dans les horaires d'ouverture du traiteur
            $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            $jourSemaine = $joursSemaine[$commande->getDatePrestation()->format('N') - 1];
            $horaire = $horaireRepository->findOneBy(['jour' => $jourSemaine]);

            if ($horaire && ($commande->getHeureLivraison() < $horaire->getHeureOuverture() || $commande->getHeureLivraison() > $horaire->getHeureFermeture())) {
                $this->addFlash('error', sprintf(
                    'L\'heure de livraison doit être comprise entre %s et %s le %s.',
                    $horaire->getHeureOuverture(),
                    $horaire->getHeureFermeture(),
                    $jourSemaine
                ));

                return $this->redirectToRoute('app_commande_nouvelle', ['menuId' => $menuId]);
            }

            // Données saisies stockées temporairement en session, le temps de la confirmation
            $request->getSession()->set('commande_en_cours', [
                'menuId' => $menuId,
                'datePrestation' => $commande->getDatePrestation()->format('Y-m-d'),
                'heureLivraison' => $commande->getHeureLivraison(),
                'adresseLivraison' => $commande->getAdresseLivraison(),
                'villeLivraison' => $commande->getVilleLivraison(),
                'nbPersonnes' => $commande->getNbPersonnes(),
                'pretMateriel' => $commande->isPretMateriel(),
            ]);

            return $this->redirectToRoute('app_commande_recapitulatif');
        }

        return $this->render('commande/nouvelle.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_commande_recapitulatif')]
    public function recapitulatif(Request $request, MenuRepository $menuRepository): Response
    {
        $donnees = $request->getSession()->get('commande_en_cours');

        if (!$donnees) {
            return $this->redirectToRoute('app_menu');
        }

        $menu = $menuRepository->find($donnees['menuId']);

        if (!$menu) {
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        // Recalcul du prix pour vérifier les données transmises côté client
        $prixMenu = $menu->getPrixBase() * $donnees['nbPersonnes'];
        $reductionAppliquee = false;

        // Si le nombre de personnes dépasse le minimum de 5 ou plus, une réduction de 10% est appliquée
        if ($donnees['nbPersonnes'] >= $menu->getNbPersonnesMin() + 5) {
            $prixMenu = $prixMenu * 0.9;
            $reductionAppliquee = true;
        }

        // Livraison gratuite à Bordeaux, sinon un forfait s'applique
        $prixLivraison = 0;
        if (strtolower($donnees['villeLivraison']) !== 'bordeaux') {
            $prixLivraison = 5.00;
        }

        $prixTotal = $prixMenu + $prixLivraison;

        return $this->render('commande/recapitulatif.html.twig', [
            'menu' => $menu,
            'donnees' => $donnees,
            'prixMenu' => $prixMenu,
            'prixLivraison' => $prixLivraison,
            'prixTotal' => $prixTotal,
            'reductionAppliquee' => $reductionAppliquee,
        ]);
    }

    #[Route('/commande/confirmer', name: 'app_commande_confirmer', methods: ['POST'])]
    public function confirmer(Request $request, MenuRepository $menuRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, HoraireRepository $horaireRepository, DocumentManager $documentManager): Response
    {
        $donnees = $request->getSession()->get('commande_en_cours');

        if (!$donnees) {
            return $this->redirectToRoute('app_menu');
        }

        $menu = $menuRepository->find($donnees['menuId']);

        if (!$menu || $menu->getStockDisponible() <= 0) {
            $this->addFlash('error', 'Ce menu n\'est malheureusement plus disponible.');
            return $this->redirectToRoute('app_menu');
        }

        // Recalcul de la date limite
        $dateLimite = new \DateTime();
        $dateLimite->modify('+' . $menu->getDelaiMinimumJours() . ' days');
        $datePrestation = new \DateTime($donnees['datePrestation']);

        if ($datePrestation < $dateLimite) {
            $this->addFlash('error', sprintf(
                'Ce menu doit être commandé au moins %d jours avant la date de prestation.',
                $menu->getDelaiMinimumJours()
        ));

            return $this->redirectToRoute('app_menu');
        }

        // L'heure de livraison doit être comprise dans les horaires d'ouverture du traiteur
        $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $jourSemaine = $joursSemaine[$datePrestation->format('N') - 1];
        $horaire = $horaireRepository->findOneBy(['jour' => $jourSemaine]);

        if ($horaire && ($donnees['heureLivraison'] < $horaire->getHeureOuverture() || $donnees['heureLivraison'] > $horaire->getHeureFermeture())) {
            $this->addFlash('error', sprintf(
                'L\'heure de livraison doit être comprise entre %s et %s le %s.',
                $horaire->getHeureOuverture(),
                $horaire->getHeureFermeture(),
                $jourSemaine
            ));

            return $this->redirectToRoute('app_menu');
        }

        // Recalcul du prix pour vérifier les données transmises côté client
        $prixMenu = $menu->getPrixBase() * $donnees['nbPersonnes'];
        if ($donnees['nbPersonnes'] >= $menu->getNbPersonnesMin() + 5) {
            $prixMenu = $prixMenu * 0.9;
        }

        $prixLivraison = 0;
        if (strtolower($donnees['villeLivraison']) !== 'bordeaux') {
            $prixLivraison = 5.00;
        }

        $prixTotal = $prixMenu + $prixLivraison;

        $commande = new Commande();
        $commande->setNumeroCommande('CMD-' . uniqid());
        $commande->setDateCommande(new \DateTime());
        $commande->setDatePrestation(new \DateTime($donnees['datePrestation']));
        $commande->setHeureLivraison($donnees['heureLivraison']);
        $commande->setAdresseLivraison($donnees['adresseLivraison']);
        $commande->setVilleLivraison($donnees['villeLivraison']);
        $commande->setNbPersonnes($donnees['nbPersonnes']);
        $commande->setPrixMenu((string) $prixMenu);
        $commande->setPrixLivraison((string) $prixLivraison);
        $commande->setPrixTotal((string) $prixTotal);
        $commande->setPretMateriel($donnees['pretMateriel']);
        $commande->setUtilisateur($this->getUser());
        $commande->setMenu($menu);

        // Sauvegarde isolée en base pour informer l'utilisateur en cas d'échec
        try {
            $entityManager->persist($commande);

            $menu->setStockDisponible($menu->getStockDisponible() - 1);

            // Création du premier statut de l'historique de la commande
            $statutHistorique = new StatutHistorique();
            $statutHistorique->setStatut('reçue');
            $statutHistorique->setDateModification(new \DateTime());
            $statutHistorique->setCommande($commande);
            $entityManager->persist($statutHistorique);

            $entityManager->flush();

        // Enregistrement de la statistique dans MongoDB pour les rapports admin
        try {
            $statistique = new StatistiqueCommande();
            $statistique->setMenuId($menu->getId());
            $statistique->setMenuTitre($menu->getTitre());
            $statistique->setDateCommande(new \DateTime());
            $statistique->setMontant((float) $prixTotal);

            $documentManager->persist($statistique);
            $documentManager->flush();

        } catch (\Exception $e) {
            // Erreur tracée sans blocage de la validation de commande, statistiques non bloquantes
        }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la commande.');
            return $this->redirectToRoute('app_commande_recapitulatif');
        }

        // Nettoyage de la session : commande finalisée
        $request->getSession()->remove('commande_en_cours');

        // Envoi du mail de confirmation isolé pour ne pas bloquer la commande si Mailtrap échoue
        try {
            $email = (new TemplatedEmail())
                ->from(new Address('contact@vite-et-gourmand.com', 'Vite & Gourmand'))
                ->to((string) $this->getUser()->getEmail())
                ->subject('Confirmation de votre commande')
                ->htmlTemplate('emails/confirmation_commande.html.twig')
                ->context([
                    'commande' => $commande,
                    'menu' => $menu,
                ]);
            $mailer->send($email);
        } catch (\Exception $e) {
            // Erreur tracée sans blocage de validation de commande
        }

        $this->addFlash('success', 'Merci ! Votre commande a bien été enregistrée. Vous allez recevoir un mail de confirmation.');
        return $this->redirectToRoute('app_accueil');
    }
}