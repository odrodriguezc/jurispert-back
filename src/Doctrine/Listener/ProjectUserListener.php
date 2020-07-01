<?php

namespace App\Doctrine\Listener;

use App\Entity\Participation;
use App\Entity\Project;
use Symfony\Component\Security\Core\Security;

class ProjectUserListener
{

  protected Security $security;

  public function __construct(Security $security)
  {
    $this->security =  $security;
  }


  public function prePersist(Project $project)
  {
    if (!$project->getOwner()) {
      $project->setOwner($this->security->getUser());
    }
  }
}
