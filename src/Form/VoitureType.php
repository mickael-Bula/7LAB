<?php

namespace App\Form;

use App\Entity\Constructeur;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', TextType::class, ['label' => 'le nom'])
            ->add('constructor', EntityType::class, [
                'label' => 'le constructeur du véhicule',
                'class' => Constructeur::class,
                'choice_label' => 'name',
                // le placeholder évite d'avoir un constructeur présélectionné
                'placeholder' => 'Sélectionner un constructeur',
                'multiple' => false,
                'required' => true,
            ])
            ->add('length',TextType::class, ['label' => 'la longueur'])
            ->add('width',TextType::class, ['label' => 'la largeur'])
            ->add('weight',TextType::class, ['label' => 'le poids'])
            ->add('seat', NumberType::class, ['label' => 'le nombre de sièges'])
            ->add('energy', ChoiceType::class, [
                'label' => 'le carburant',
                // le placeholder évite une sélection par défaut
                'placeholder' => 'Choisir le carburant',
                'choices' => [
                    'Sans plomb' => 'sans plomb',
                    'Diesel' => 'diesel',
                    'Electrique' => 'électrique',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
