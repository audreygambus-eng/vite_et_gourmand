<?php

namespace App\Form;

use App\Entity\Horaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class HoraireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jour', TextType::class,[
                'disabled' => true,
            ])
            ->add('heureOuverture', TextType::class,[
                'constraints' =>[new NotBlank(message:'Veuillez renseigner une heure d\'ouverture')],
            ])
            ->add('heureFermeture', TextType::class,[
                'constraints' =>[new NotBlank(message:'Veuillez renseigner une heure de fermeture')],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaire::class,
        ]);
    }
}
