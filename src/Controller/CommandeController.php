<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande/nouvelle/{menuId}', name: 'app_commande_nouvelle')]
    public function nouvelle(int $menuId, MenuRepository $menuRepository, Request $request): Response
    {
        
        // Vérification de la connexion utilisateur avec information et redirection vers la page de connexion, le cas échéant
        if (!$this->getUser()) {
            $this->addFlash('info', 'Veuillez vous connecter ou créer un compte pour passer commande.');
            $request->getSession()->set('_security.main.target_path', $this->generateUrl('app_commande_nouvelle', ['menuId' => $menuId]));

            return $this->redirectToRoute('app_login');
        }

        $menu = $menuRepository->find($menuId);

        if (!$menu) {
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        return $this->render('commande/nouvelle.html.twig', [
            'menu' => $menu,
        ]);
    }
}
