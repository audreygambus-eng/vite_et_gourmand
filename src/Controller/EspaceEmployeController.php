<?php

namespace App\Controller;

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
}
