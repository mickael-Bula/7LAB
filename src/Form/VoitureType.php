<?php

namespace App\Form;

use App\Entity\Constructeur;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', TextType::class, [
                'label' => 'le nom',
                'empty_data' => '',
            ])
            ->add('constructor', EntityType::class, [
                'label' => 'le constructeur du véhicule',
                'class' => Constructeur::class,
                'choice_label' => 'name',
                // le placeholder évite d'avoir un constructeur présélectionné
                'placeholder' => 'Sélectionner un constructeur',
                'multiple' => false,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('length',NumberType::class, [
                'label' => 'la longueur',
                'invalid_message' => 'Veuillez saisir un nombre',])
            ->add('width',NumberType::class, [
                'label' => 'la largeur',
                'invalid_message' => 'Veuillez saisir un nombre',
                ])
            ->add('weight',NumberType::class, [
                'label' => 'le poids',
                'invalid_message' => 'Veuillez saisir un nombre',
                ])
            ->add('seat', IntegerType::class, [
                'label' => 'le nombre de sièges',
                // pour afficher un message quand le type saisi ne correspond au type attendu (ici un entier)
                'invalid_message' => 'Veuillez saisir un nombre entier',
                ])
            ->add('energy', ChoiceType::class, [
                'label' => 'le carburant',
                // le placeholder évite une sélection par défaut
                'placeholder' => 'Choisir le carburant',
                'choices' => [
                    'Sans plomb' => 'sans plomb',
                    'Diesel' => 'diesel',
                    'Electrique' => 'électrique',
                ],
                'empty_data' => '',
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
