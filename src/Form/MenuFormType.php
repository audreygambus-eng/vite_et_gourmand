<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Regime;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'constraints' => [new NotBlank(message : 'Veuillez renseigner un titre')],
            ])
            ->add('description', TextareaType::class,[
                'constraints' => [new NotBlank(message : 'Veuillez ajouter une description')],
            ])
            ->add('conditions', TextareaType::class,[
                'required' => false,
            ])
            ->add('nbPersonnesMin', IntegerType::class,[
                'constraints' => [new Positive(message: "Le nombre de personnes doit être un nombre positif.")]
            ])
            ->add('prixBase', NumberType::class,[
                'constraints' => [new Positive(message: 'Le prix renseigné doit être positif')],
            ])
            ->add('stockDisponible', IntegerType::class)
            ->add('delaiMinimumJours', IntegerType::class)
            ->add('actif', CheckboxType::class,[
                'required' => false,
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'libelle',
            ])
            ->add('regime', EntityType::class, [
                'class' => Regime::class,
                'choice_label' => 'libelle',
            ])
            ->add('plats', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
