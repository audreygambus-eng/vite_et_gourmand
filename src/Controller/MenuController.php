<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\ThemeRepository;
use App\Repository\RegimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenuRepository $menuRepository, ThemeRepository $themeRepository, RegimeRepository $regimeRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findActifs(),
            'themes' => $themeRepository->findAll(),
            'regimes' => $regimeRepository->findAll(),
        ]);
    }

    // Route API qui retourne du JSON et qui est appelée par le JS côté client
    #[Route('/menu/filter', name: 'app_menu_filter', methods: ['GET'])]
    public function filter(Request $request, MenuRepository $menuRepository) : JsonResponse
    {
        // Validation des paramètres
        $prixMax = $request->query->get('prixMax');
        if (!is_numeric($prixMax)) {
            $prixMax = null;
        }

        $themeId = $request->query->get('theme');
        if (!ctype_digit($themeId ?? '')) {
            $themeId = null;
        }

        $regimeId = $request->query->get('regime');
        if (!ctype_digit($regimeId ?? '')) {
            $regimeId = null;
        }

        $nbPersonnes = $request->query->get('nbPersonnes');
        if (!is_numeric($nbPersonnes)) {
            $nbPersonnes = null;
        }

        $menus = $menuRepository->findFiltres($prixMax, $themeId, $regimeId, $nbPersonnes);

        $data = [];
        foreach ($menus as $menu){
            $data[] = [
                'id' => $menu->getId(),
                'titre' => $menu->getTitre(),
                'description' => $menu->getDescription(),
                'nbPersonnesMin' => $menu->getNbPersonnesMin(),
                'prixBase' => $menu->getPrixBase(),
            ];
        }

        return new JsonResponse($data);
    
    }
}
