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
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('email', EmailType::class)
            
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

