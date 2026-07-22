<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('accueil/index.html.twig', [
            'avis' => $avisRepository->findValides(),
        ]);
    }
}
