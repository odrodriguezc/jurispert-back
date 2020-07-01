<?php

namespace App\Doctrine\Listener;

use App\Entity\Participation;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProjectParticipationListener
{
  protected EntityManagerInterface $em;
  protected Security $security;

  public function __construct(EntityManagerInterface $em, Security $security)
  {
    $this->em = $em;
    $this->security = $security;
  }

  public function prePersist(Project $project)
  {
    $participation = new Participation;
    $participation
      ->setUser($this->security->getUser())
      ->setRole('CREATOR')
      ->setProject($project);
    $this->em->persist($participation);
    $project->addParticipation($participation);
  }
}
