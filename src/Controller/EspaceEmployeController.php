<?php

namespace App\Controller;

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
}
