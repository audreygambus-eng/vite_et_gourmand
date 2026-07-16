<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Entity\StatutHistorique;
use App\Repository\CommandeRepository;
use App\Form\HoraireFormType;
use App\Repository\HoraireRepository;
use App\Form\PlatFormType;
use App\Repository\PlatRepository;
use App\Form\MenuFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EspaceEmployeController extends AbstractController
{
    #[Route('/espace/employe', name: 'app_espace_employe')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function index(): Response
    {
        return $this->render('espace_employe/index.html.twig');
    }

    #[Route('/espace/employe/menus', name: 'app_espace_employe_menus')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function menus(MenuRepository $menuRepository): Response
    {
        return $this->render('espace_employe/menus.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('/espace/employe/menus/{id}/modifier', name: 'app_espace_employe_menu_modifier')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function menuModifier(int $id, Request $request, MenuRepository $menuRepository, EntityManagerInterface $entityManager): Response
    {
            $menu = $menuRepository->find($id);

            if (!$menu){
                throw $this->createNotFoundException('Ce menu n\'existe pas.');
            }

            $form = $this->createForm(MenuFormType::class, $menu);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $entityManager->flush();

                $this->addFlash('success', 'Le menu a bien été modifié.');
                return $this->redirectToRoute('app_espace_employe_menus');
            }

            return $this->render('espace_employe/menu_modifier.html.twig', [
            'menu' => $menu,
            'form' => $form
            ]);
    }

    #[Route('/espace/employe/menus/{id}/supprimer', name: 'app_espace_employe_menu_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function menuSupprimer(int $id, MenuRepository $menuRepository, EntityManagerInterface $entityManager): Response
    {
        $menu = $menuRepository->find($id);

        if (!$menu){
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        // Supression du catalogue uniquement, pour que le menu reste accessible en base et réactivable, si besoin
        $menu->setActif(false);
        $entityManager->flush();

        $this->addFlash('success', 'Le menu a bien été désactivé.');
        return $this->redirectToRoute('app_espace_employe_menus');

    }

    #[Route('/espace/employe/plats', name: 'app_espace_employe_plats')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function plats(PlatRepository $platRepository): Response
    {
        return $this->render('espace_employe/plats.html.twig', [
            'plats' => $platRepository->findAll(),
        ]);
    }

    #[Route('/espace/employe/plats/{id}/modifier', name: 'app_espace_employe_plat_modifier')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function platModifier(int $id, Request $request, PlatRepository $platRepository, EntityManagerInterface $entityManager): Response
    {
        $plat = $platRepository->find($id);

        if (!$plat){
            throw $this->createNotFoundException('Ce plat n\'existe pas.');
        }

        $form = $this->createForm(PlatFormType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le plat a bien été modifié.');
            return $this->redirectToRoute('app_espace_employe_plats');
        }

        return $this->render('espace_employe/plat_modifier.html.twig',[
            'plat' => $plat,
            'form' => $form
        ]);
    }

    #[Route('/espace/employe/plats/{id}/supprimer', name: 'app_espace_employe_plat_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function platSupprimer(int $id, PlatRepository $platRepository, EntityManagerInterface $entityManager): Response
    {
        $plat = $platRepository->find($id);

        if(!$plat){
            throw $this->createNotFoundException('Ce plat n\'existe pas.');
        }

        $entityManager->remove($plat);
        $entityManager->flush();

        $this->addFlash('success', 'Le plat a bien été supprimé.');
        return $this->redirectToRoute('app_espace_employe_plats');
    }

    #[Route('/espace/employe/horaires', name: 'app_espace_employe_horaires')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function horaires(HoraireRepository $horaireRepository): Response
    {
        return $this->render('espace_employe/horaires.html.twig', [
            'horaires' => $horaireRepository->findAll(),
        ]);
    }

    #[Route('/espace/employe/horaires/{id}/modifier', name: 'app_espace_employe_horaire_modifier')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function horaireModifier(int $id, Request $request, HoraireRepository $horaireRepository, EntityManagerInterface $entityManager): Response
    {
    $horaire = $horaireRepository->find($id);

    if (!$horaire){
        throw $this->createNotFoundException('Cet horaire n\'existe pas.');
    }

    $form = $this->createForm(HoraireFormType::class, $horaire);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Les horaires ont bien été modifiés');
        return $this->redirectToRoute('app_espace_employe_horaires');
    }

    return $this->render('espace_employe/horaire_modifier.html.twig', [
        'horaire' => $horaire,
        'form' => $form
    ]);
    }

    #[Route('/espace/employe/commandes', name: 'app_espace_employe_commandes')]
    #[IsGranted('ROLE_EMPLOYE')]
    // Filtres transmis en GET : pas d'exigence de non rechargement de page pour l'espace employé
    public function commandes(Request $request, CommandeRepository $commandeRepository): Response
    {
        $statut = $request->query->get('statut');
        $client = $request->query->get('client');

        return $this->render('espace_employe/commandes.html.twig',[
            'commandes' => $commandeRepository->findFiltrees($statut, $client),
            'statutSelectionne' => $statut,
            'clientRecherche' => $client
        ]);
    }

    #[Route('/espace/employe/commandes/{id}', name: 'app_espace_employe_commande_gerer')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function commanderGerer(int $id, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('Cette commande n\'existe pas.');
        }

        return $this->render('espace_employe/commande_gerer.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/espace/employe/commandes/{id}/statut', name: 'app_espace_employe_commande_statut', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function commandeStatut(int $id, Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

        if(!$commande) {
            throw $this->createNotFoundException('Cette commande n\'existe pas.');
        }

        $nouveauStatut = $request->request->get('statut');

        // Statut existant non modifié : chaque changement crée une nouvelle entrée
        // Historique complet conservé et consultable
        $statutHistorique = new StatutHistorique();
        $statutHistorique->setStatut($nouveauStatut);
        $statutHistorique->setDateModification(new \DateTime());
        $statutHistorique->setCommande($commande);
        $entityManager->persist($statutHistorique);
        $entityManager->flush();

        $this->addFlash('success', 'Le statut de la commande a bien été mis à jour.');
        return $this->redirectToRoute('app_espace_employe_commande_gerer', ['id' => $id]);

    }

    #[Route('/espace/employe/commandes/{id}/annuler', name: 'app_espace_employe_commande_annuler', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function commandeAnnuler(int $id, Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

    if (!$commande) {
        throw $this->createNotFoundException('Cette commande n\'existe pas.');
    }

    $motif = $request->request->get('motif');
    $modeContact = $request->request->get('modeContact');

    //  Protection contre un texte trop long
    if (strlen($motif) > 500) {
        $motif = substr($motif, 0, 500);
    }
    // Le motif et le mode de contact sont obligatoires pour toute annulation par un employé
    $statutHistorique = new StatutHistorique();
    $statutHistorique->setStatut('annulée');
    $statutHistorique->setDateModification(new \DateTime());
    $statutHistorique->setCommande($commande);
    $statutHistorique->setCommentaire(sprintf(
        'motif : %s (Contact : %s)',
        $motif,
        $modeContact
    ));
    $entityManager->persist($statutHistorique);

    // Stock réincrémenté, même procédé que pour l'annulation par l'utilisateur
    $menu = $commande->getMenu();
    $menu->setStockDisponible($menu->getStockDisponible() + 1);
    $entityManager->flush();

    $this->addFlash('success', 'La commande a bien été annulée.');
    return $this->redirectToRoute('app_espace_employe_commandes');
    }

    #[Route('/espace/employe/avis', name: 'app_espace_employe_avis')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function avis(AvisRepository $avisRepository): Response
    {
        return $this->render('espace_employe/avis.html.twig', [
            'avisEnAttente' => $avisRepository->findEnAttente(),
        ]);
    }

    #[Route('/espace/employe/avis/{id}/valider', name: 'app_espace_employe_avis_valider', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function avisValider(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): Response
    {
        $avis = $avisRepository->find($id);

        if (!$avis){
            throw $this->createNotFoundException('Cet avis n\'existe pas.');
        }

        $avis->setValide(true);
        $entityManager->flush();

        $this->addFlash('success', 'L\'avis a bien été validé.');
        return $this->redirectToRoute('app_espace_employe_avis');
    }

    #[Route('/espace/employe/avis/{id}/refuser', name: 'app_espace_employe_avis_refuser', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYE')]
    public function avisRefuser(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): Response
    {
        $avis = $avisRepository->find($id);

        if (!$avis){
            throw $this->createNotFoundException('Cet avis n\'existe pas.');
        }

        $entityManager->remove($avis);
        $entityManager->flush();

        $this->addFlash('success', 'L\'avis a bien été refusé.');
        return $this->redirectToRoute('app_espace_employe_avis');
    }
}
