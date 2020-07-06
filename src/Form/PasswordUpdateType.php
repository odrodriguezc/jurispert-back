<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordUpdateType extends AbstractType
{
  protected UserPasswordEncoderInterface $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->encoder = $encoder;
  }



  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('password', RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Confirm ']
      ]);

    $builder->addEventListener(FormEvents::POST_SUBMIT, function ($event) {
      $user = $event->getData();
      $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
    });
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
