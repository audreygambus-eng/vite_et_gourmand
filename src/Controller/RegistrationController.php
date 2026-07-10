<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address; // Affichage du nom de l'expéditeur, associé à son adresse mail
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RoleRepository; // Récupération du rôle utilisateur en base
use Psr\Log\LoggerInterface; // Traçage des erreurs d'envoi de mails


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, RoleRepository $roleRepository, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']); // Attribution du rôle Symfony natif
            $user->setActif(true);

            // Récupération du rôle "ROLE_USER" depuis l'entité Role
            $roleUser = $roleRepository->findOneBy(['libelle' => 'ROLE_USER']);
            // Lien avec l'utilisateur
            $user->setRole($roleUser);

            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi du mail isolé pour ne pas bloquer l'inscription si Mailtrap échoue
            try{
                $email = (new TemplatedEmail())
                    ->from(new Address('contact@vite-et-gourmand.com', 'Vite & Gourmand'))
                    ->to((string) $user->getEmail())
                    ->subject('Bienvenue chez Vite & Gourmand !')
                    ->htmlTemplate('emails/bienvenue.html.twig')
                    ->context([
                        'prenom' => $user->getPrenom(),
                    ]);
                $mailer->send($email);
            }
            catch (\Exception $e) {
                // Erreur tracée sans blocage d'inscription
                $logger->error('Erreur envoi mail de bienvenue : ' . $e->getMessage());
            }
            return $security->login($user, LoginFormAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
