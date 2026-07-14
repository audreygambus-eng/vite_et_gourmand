<?php

namespace App\Controller;

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
}
