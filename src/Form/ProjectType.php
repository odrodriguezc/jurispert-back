<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du projet'
            ])
            ->add('shortDescription', TextType::class, [
                'label' => 'Presentation du projet'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du projet'
            ])
            ->add('deadline', DateTimeType::class, [
                'label' => 'Date butoir'
            ])
            ->add('adversary', TextType::class, [
                'label' => 'Adversaire'
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Categorie',
                'choices' => [
                    'Prototype' => 'PROTOTYPE',
                ],
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'lastName',
                'multiple' => true,
                'placeholder' => '-- Selectionez les clients --',
                'label' => 'Client'
            ])
            ->add('participations', CollectionType::class, [
                'entry_type' => ParticipationType::class,
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
