<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('page/mentions_legales.html.twig');
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $donnees = $form->getData();

            try{
                $email = (new TemplatedEmail())
                    ->from(new Address($donnees['email']))
                    ->to(new Address('contact@vite-et-gourmand.com', 'Vite & Gourmand'))
                    ->subject('Nouveau message de contact : ' . $donnees['titre'])
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context([
                        'titre' => $donnees['titre'],
                        'expediteurEmail' => $donnees['email'],
                        'description' => $donnees['description'],
                    ]);
                $mailer->send($email);

                $this->addFlash('success', 'Votre message a bien été envoyé. Notre équipe vous répondra dans les meilleurs délais.');
                return $this->redirectToRoute('app_contact');
            } catch (\Exception $e){
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.');
            }
        }

        return $this->render('page/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
