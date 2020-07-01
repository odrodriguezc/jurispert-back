<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtDataSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      Events::JWT_CREATED => 'addUserInformation'
    ];
  }


  public function addUserInformation(JWTCreatedEvent $event)
  {
    /** @var User */
    $user = $event->getUser();

    $data = $event->getData();

    $data['user'] = [
      'id' => $user->getId(),
      'firstName' => $user->getFirstName(),
      'lastName' => $user->getLastName(),
      'email' => $user->getEmail(),
      'roles' => $user->getRoles()
    ];

    $event->setData($data);
  }
}
