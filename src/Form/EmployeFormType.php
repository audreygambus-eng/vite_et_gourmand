<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use App\Form\PasswordConstraints;

class EmployeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [new NotBlank(message: 'Veuillez renseigner un nom')],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [new NotBlank(message: 'Veuillez renseigner un prénom')],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank(message: 'Veuillez renseigner une adresse mail')],
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'exemple : 06 11 93 66 17',
                    'pattern' => '0[1-9]([ .]?[0-9]{2}){4}',
                    'title' => 'Format attendu : 06 11 93 66 17',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe de l\'employé',
                'mapped' => false,
                'attr' => array_merge(
                    ['autocomplete' => 'new-password'],
                    PasswordConstraints::getHtmlAttributes(),
                ),
                'constraints' => [
                    new NotBlank(message: 'Veuillez définir un mot de passe'),
                    new Length(min: 10, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères'),
                    new Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        message: 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial'
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

