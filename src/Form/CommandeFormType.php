<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePrestation', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner la date souhaitée pour la prestation.'),
                ],
            ])
            ->add('heureLivraison', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner l\'heure à laquelle la commande doit être livrée.'),
                ],
            ])
            ->add('adresseLivraison', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner l\'adresse du lieu de livraison.'),
                ],
            ])
            ->add('villeLivraison', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner la ville de livraison.'),
                ],
            ])
            ->add('nbPersonnes', IntegerType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez renseigner le nombre de personnes concernées par la commande.'),
                    new GreaterThanOrEqual(value: 1, message: 'Le nombre de personnes minimum requis pour une commande est de 1'),
                ],
            ])
            ->add('pretMateriel', CheckboxType::class, [
                'label' => 'Je souhaite faire une demande de prêt de matériel',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
