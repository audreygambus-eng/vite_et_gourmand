<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre nom'),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre prénom'),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'exemple@email.com'],
                ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'exemple : 06 11 93 66 17',
                    'pattern' => '0[1-9]([ .]?[0-9]{2}){4}',
                    'title' => 'Format attendu : 06 11 93 66 17',
                ],
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'exemple : 10 rue du Bon Traiteur'],
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'exemple : Bordeaux'],
            ])
            ->add('codePostal', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'exemple : 33000',
                    'pattern' => '[0-9]{5}',
                    'title' => 'Le code postal doit contenir 5 chiffres',
                    'maxlength' => 5,
                ],
            ])
            ->add('pays', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'exemple : France'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => array_merge(
                    ['autocomplete' => 'new-password'],
                    PasswordConstraints::getHtmlAttributes()
                ),
                'constraints' => PasswordConstraints::getConstraints(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
