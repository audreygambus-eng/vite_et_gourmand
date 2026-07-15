<?php

namespace App\Form;

use App\Entity\Allergene;
use App\Entity\Menu;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [new NotBlank(message: 'Veuillez renseigner un titre.')],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Entrée' => 'entrée',
                    'Plat' => 'plat',
                    'Dessert' => 'dessert',
                ],
                'constraints' => [new NotBlank(message: "Veuillez choisir un type de plat.")],
            ])
            ->add('description', TextareaType::class,[
                'required' => false,
            ])
            ->add('menus', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
            ->add('allergenes', EntityType::class, [
                'class' => Allergene::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
