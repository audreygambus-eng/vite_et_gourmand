<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Form\PasswordConstraints;

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
                'attr' => ['placeholder' => 'exemple : 06 11 93 66 17'],
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
                'attr' => ['placeholder' => 'exemple : 33000'],
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
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez choisir un mot de passe',
                    ),
                    new Length(
                        min: 10,
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        max: 128,
                        maxMessage: 'Votre mot de passe ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        message: 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial',
                    ),
                ],
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
