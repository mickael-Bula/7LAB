<?php

namespace App\Form;

use App\Entity\Constructeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstructeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'le nom',
                'empty_data' => '',])
            ->add('country',TextType::class, [
                'label' => 'le pays',
                'empty_data' => '',
            ])
            ->add('site',TextType::class, [
                'label' => "l'adresse du site",
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Constructeur::class,
        ]);
    }
}
