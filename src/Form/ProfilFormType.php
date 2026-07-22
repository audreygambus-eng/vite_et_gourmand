<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner votre nom'),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner votre prénom'),
                ],
            ])
            ->add('telephone', TelType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'exemple : 06 11 93 66 17'],
            ])
            ->add('adresse', TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'exemple : 10 rue du Bon Traiteur'],
            ])
            ->add('ville', TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'exemple : Bordeaux'],
            ])
            ->add('codePostal', TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'exemple : 33000'],
            ])
            ->add('pays', TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'exemple : France'],
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
