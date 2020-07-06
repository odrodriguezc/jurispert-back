<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstName',
                'placeholder' => '-- Selectionner un utilisateur',
                'label' => 'Participants'
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Gestionnaire' => 'MANAGER',
                    'Assistant' => 'VIEWER'
                ],
                'placeholder' => '-- Sélectionner un rôle',
                'label' => 'Rôle dans le projet'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
