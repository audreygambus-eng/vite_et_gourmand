<?php

namespace App\Controller;

use App\Document\StatistiqueCommande;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Utilisateur;
use App\Form\EmployeFormType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EspaceAdminController extends AbstractController
{
    #[Route('/espace/admin', name: 'app_espace_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('espace_admin/index.html.twig');
    }

    #[Route('/espace/admin/employes', name: 'app_espace_admin_employes')]
    #[IsGranted('ROLE_ADMIN')]
    public function employes(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('espace_admin/employes.html.twig', [
            'employes' => $utilisateurRepository->findEmployes(),
        ]);
    }

    #[Route('/espace/admin/employes/creer', name: 'app_espace_admin_employe_creer')]
    #[IsGranted('ROLE_ADMIN')]
    public function employeCreer(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, RoleRepository $roleRepository, MailerInterface $mailer): Response
    {
        $employe = new Utilisateur();
        $form = $this->createForm(EmployeFormType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // MDP défini par l'administrateur
            $plainPassword = $form->get('plainPassword')->getData();

            $employe->setPassword($userPasswordHasher->hashPassword($employe, $plainPassword));
            $employe->setRoles(['ROLE_EMPLOYE']);
            $employe->setActif(true);

            $roleEmploye = $roleRepository->findOneBy(['libelle' => 'ROLE_EMPLOYE']);
            $employe->setRole($roleEmploye);

            try {
                $entityManager->persist($employe);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création du compte.');
                return $this->redirectToRoute('app_espace_admin_employe_creer');
            }

            // L'employé doit être informé que son compte a été créé, sans que le MDP lui soit donné dans le mail
            try {
                $email = (new TemplatedEmail())
                    ->from(new Address('contact@vite-et-gourmand.com', 'Vite & Gourmand'))
                    ->to((string) $employe->getEmail())
                    ->subject('Création de votre compte employé')
                    ->htmlTemplate('emails/creation_compte_employe.html.twig')
                    ->context([
                        'employe' => $employe,
                    ]);
                $mailer->send($email);
            } catch (\Exception $e) {
                // Erreur tracée sans blocage de création de compte
            }

            $this->addFlash('success', 'Le compte employé a bien été créé.');
            return $this->redirectToRoute('app_espace_admin_employes');

        }

        return $this->render('espace_admin/employe_creer.html.twig', [
        'form' => $form,
        ]);
    }

    #[Route('/espace/admin/employes/{id}/toggle', name: 'app_espace_admin_employe_toggle', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function employeToggle(int $id, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        $employe = $utilisateurRepository->find($id);

        if (!$employe) {
            throw $this->createNotFoundException('Cet employé n\'existe pas.');
        }

        $employe->setActif(!$employe->isActif());
        $entityManager->flush();

        if ($employe->isActif()) {
            $this->addFlash('success', 'Le compte a bien été réactivé.');
        } else {
            $this->addFlash('success', 'Le compte a bien été désactivé.');
        }
        return $this->redirectToRoute('app_espace_admin_employes');
    }

    #[Route('/espace/admin/statistiques', name: 'app_espace_admin_statistiques')]
    #[IsGranted('ROLE_ADMIN')]
    public function statistiques(Request $request, DocumentManager $documentManager): Response
    {
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');

        $aggregationBuilder = $documentManager->createAggregationBuilder(StatistiqueCommande::class);
        if ($dateDebut && $dateFin) {
            $aggregationBuilder->match()
                ->field('dateCommande')
                ->gte(new \DateTime($dateDebut))
                ->lte(new \DateTime($dateFin . ' 23:59:59'));
        }

        $resultats = $aggregationBuilder
            ->group()
                ->field('id')->expression('$menuTitre')
                ->field('nombreCommandes')->sum(1)
                ->field('chiffreAffaires')->sum('$montant')
            ->getAggregation()
            ->getIterator()
            ->toArray();

        // Calcul du chiffre d'affaires global, toutes ventes confondues sur la période filtrée
        $caTotal = 0;
        foreach ($resultats as $resultat) {
            $caTotal += $resultat['chiffreAffaires'];
        }
        
        return $this->render('espace_admin/statistiques.html.twig', [
            'resultats' => $resultats,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'caTotal' => $caTotal,
        ]);
    }
}