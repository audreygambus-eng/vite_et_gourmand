<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class,[
                'choices' => [
                    '1 étoile' => 1,
                    '2 étoiles' => 2,
                    '3 étoiles' => 3,
                    '4 étoiles' => 4,
                    '5 étoiles' => 5,

                ],

                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir une note'),
                ],
            ])

            ->add('commentaire', TextareaType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez laisser un commentaire'),
                    new Length(max: 1000, maxMessage: 'Votre commentaire ne doit pas dépasser {{ limit }} caractères'),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
