<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use App\Form\PasswordConstraints;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'attr' => array_merge(
                        ['autocomplete' => 'new-password'],
                        PasswordConstraints::getHtmlAttributes()
                    ),
                    'constraints' => [
                        new NotBlank(
                            message: 'Veuillez entrer un mot de passe',
                        ),
                        new Length(
                            min: 10,
                            minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            max: 4096,
                        ),
                        new PasswordStrength(
                            message: 'Votre mot de passe est trop faible. Veuillez utiliser un mot de passe plus fort.',
                        ),
                        new NotCompromisedPassword(
                            message: 'Ce mot de passe a été exposé lors d\'une fuite de données. Veuillez en choisir un autre.',
                        ),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                ],
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
