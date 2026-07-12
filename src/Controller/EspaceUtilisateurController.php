<?php

namespace App\Controller;

use App\Repository\HoraireRepository;
use App\Entity\StatutHistorique;
use App\Form\CommandeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EspaceUtilisateurController extends AbstractController
{
    #[Route('/espace/utilisateur', name: 'app_espace_utilisateur')]
    #[IsGranted('ROLE_USER')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('espace_utilisateur/index.html.twig', [
            'commandes' => $commandeRepository->findByUtilisateur($this->getUser()),
        ]);
    }

    #[Route('/espace/utilisateur/commande/{id}', name: 'app_espace_utilisateur_commande_detail')]
    #[IsGranted('ROLE_USER')]
    public function commandeDetail(int $id, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande){
            throw $this->createNotFoundException('Cette commande n\'existe pas.');
        }

        // Les autres utilisateurs ne doivent pas avoir accès aux commandes (IDOR)
        if($commande->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('espace_utilisateur/commande_detail.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/espace/utilisateur/commande/{id}/modifier', name: 'app_espace_utilisateur_commande_modifier')]
    #[IsGranted('ROLE_USER')]

    public function commandeModifier(int $id, Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager,HoraireRepository $horaireRepository): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('Cette commande n\'existe pas.');
        }

        if ($commande->getUtilisateur() !== $this->getUser()){
            throw $this->createAccessDeniedException();
        }

        // Une commande acceptée ne doit plus pouvoir être modifiée
        if (!$commande->estModifiable()){
            $this->addFlash('error', 'Cette commande a été acceptée et ne peut donc plus être modifiée');
            return $this->redirectToRoute('app_espace_utilisateur_commande_detail', ['id' => $id]);
        }

        $form = $this->createForm(CommandeFormType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $menu = $commande->getMenu();

            if ($commande->getNbPersonnes() < $menu->getNbPersonnesMin()) {
                $this->addFlash('error', sprintf(
                    'Ce menu doit concerner au moins %d personnes.',
                    $menu->getNbPersonnesMin()
                ));
                return $this->redirectToRoute('app_espace_utilisateur_commande_modifier', ['id' => $id]);
            }

            $dateLimite = new \DateTime();
            $dateLimite->modify('+' . $menu->getDelaiMinimumJours() . ' days');

            if ($commande->getDatePrestation() < $dateLimite) {
                $this->addFlash('error', sprintf(
                    'Ce menu doit être commandé au moins %d jours avant la date de prestation.',
                    $menu->getDelaiMinimumJours()
                ));
                return $this->redirectToRoute('app_espace_utilisateur_commande_modifier', ['id' => $id]);
            }

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
                return $this->redirectToRoute('app_espace_utilisateur_commande_modifier', ['id' => $id]);
            }

            $prixMenu = $menu->getPrixBase() * $commande->getNbPersonnes();
            if ($commande->getNbPersonnes() >= $menu->getNbPersonnesMin() + 5) {
                $prixMenu = $prixMenu * 0.9;
            }

            $prixLivraison = 0;
            if (strtolower($commande->getVilleLivraison()) !== 'bordeaux') {
                $prixLivraison = 5.00;
            }

            $commande->setPrixMenu((string) $prixMenu);
            $commande->setPrixLivraison((string) $prixLivraison);
            $commande->setPrixTotal((string) ($prixMenu + $prixLivraison));
            
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été modifiée');
            return $this->redirectToRoute('app_espace_utilisateur_commande_detail', ['id' => $id]);
        }

        return $this->render('espace_utilisateur/commande_modifier.html.twig',[
            'commande' => $commande,
            'form' => $form
        ]);
    }

    #[Route('/espace/utilisateur/commande/{id}/annuler', name: 'app_espace_utilisateur_commande_annuler', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]

    public function commandeAnnuler(int $id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
        throw $this->createNotFoundException('Cette commande n\'existe pas.');
        }

        if ($commande->getUtilisateur() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
        }

        if (!$commande->estModifiable()) {
        $this->addFlash('error', 'Cette commande ne peut plus être annulée car elle a déjà été acceptée.');
        return $this->redirectToRoute('app_espace_utilisateur_commande_detail', ['id' => $id]);
        }

        $statutHistorique = new StatutHistorique();
        $statutHistorique->setStatut('annulée');
        $statutHistorique->setDateModification(new \DateTime());
        $statutHistorique->setCommande($commande);
        $entityManager->persist($statutHistorique);

        // Si la commande est annulée, le stock doit être incrémenté
        $menu = $commande->getMenu();
        $menu->setStockDisponible($menu->getStockDisponible() +1);

        $entityManager->flush();

        $this->addFlash('success', 'Votre commande a bien été annulée.');
        return $this->redirectToRoute('app_espace_utilisateur');
    }
}
