<?php

namespace App\Doctrine\Listener;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\Project;
use App\Entity\Task;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProjectUserListener
{

  protected Security $security;
  protected EntityManagerInterface $em;

  public function __construct(Security $security, EntityManagerInterface $em)
  {
    $this->security =  $security;
    $this->em = $em;
  }


  public function prePersist(Project $project)
  {

    if (!$project->getOwner()) {
      $project->setOwner($this->security->getUser());
    }

    if ($project->getCategory() === 'PROTOTYPE') {
      /** @var Task[] */
      $tasks = [];
      $tasks[] = new Task();
      $tasks[0]
        ->setTitle('Appell au clients')
        ->setDescription('Appeller les clients pour fixer le prochain RV');
      $tasks[] = new Task();
      $tasks[1]
        ->setTitle('contrats de services')
        ->setDescription('Rediger les contrats de services');
      $tasks[] = new Task();
      $tasks[2]
        ->setTitle('Assurance de credit')
        ->setDescription('Assuerer les honnoraries au pres de l\'assurance');
      $tasks[] = new Task();
      $tasks[3]
        ->setTitle('Honnoraires')
        ->setDescription('Calculer les honnoraries');
    }

    $today = new DateTime();

    $event = new Event();
    $event
      ->setTitle('RDV initialle avec les clients')
      ->setAddress('Bureau ovale')
      ->setDate($today->add(new DateInterval('P10D')))
      ->setDescription('RDV avec les clients')
      ->setProject($project);

    $this->em->persist($event);

    foreach ($tasks as $key => $task) {
      $task
        ->setCompleted(false)
        ->setDeadline($project->getDeadline())
        ->setCreatedAt(new DateTime())
        ->setProject($project);
      $project->addTask($task);

      $this->em->persist($task);
    }
  }
}
