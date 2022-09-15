<?php

namespace App\Form;

use App\Entity\Constructeur;
use App\Repository\ConstructeurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Ce formulaire permet de présenter deux menus déroulants, chacun contenant une liste de cases à cocher.
 * À la soumission, une liste de véhicules filtrés est affichée en page d'accueil
 *
 */
class ConstructeurChoiceType extends AbstractType
{
    // injection du repository pour pouvoir afficher la liste des constructeurs
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
                'choices' => $this->constructeurRepository->findAll(),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('fuel', ChoiceType::class, [
                // le champ 'fuel' n'existant pas dans l'entité, je demande à ce qu'il soit ignoré
                'mapped' => false,
                'choices' => [
                    'sans plomb' => 'sans plomb',
                    'diesel' => 'diesel',
                    'électrique' => 'électrique',
                ],
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
