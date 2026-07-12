<?php

namespace App\Controller;

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

        if($commande->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('espace_utilisateur/commande_detail.html.twig', [
            'commande' => $commande
        ]);
    }
}
