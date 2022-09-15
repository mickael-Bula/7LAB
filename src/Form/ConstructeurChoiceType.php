<?php

namespace App\Form;

use App\Entity\Constructeur;
use App\Repository\ConstructeurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstructeurChoiceType extends AbstractType
{
    private $constructeurRepository;

    public function __construct(ConstructeurRepository $constructeurRepository)
    {
        $this->constructeurRepository = $constructeurRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => Constructeur::class,
                'label' => 'Constructeurs',
                'choices' => $this->constructeurRepository->findAll(),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
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
