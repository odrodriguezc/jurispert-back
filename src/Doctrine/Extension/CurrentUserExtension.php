<?php

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use App\Entity\Event;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
  protected Security $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }


  public function applyToItem(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
  {


    if ($resourceClass === Task::class) {
      $alias = $queryNameGenerator->generateJoinAlias('project');
      $aliasParticipations = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.project', $alias)
        ->join($alias . '.participations', $aliasParticipations)
        ->andWhere($aliasParticipations . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }

    if ($resourceClass === Event::class) {
      $alias = $queryNameGenerator->generateJoinAlias('project');
      $aliasParticipations = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.project', $alias)
        ->join($alias . '.participations', $aliasParticipations)
        ->andWhere($aliasParticipations . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }

    if ($resourceClass === Project::class) {
      $alias = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.participations', $alias)
        ->andWhere($alias . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }
  }

  public function applyToCollection(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
  {

    if ($resourceClass === Task::class) {
      $alias = $queryNameGenerator->generateJoinAlias('project');
      $aliasParticipations = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.project', $alias)
        ->join($alias . '.participations', $aliasParticipations)
        ->andWhere($aliasParticipations . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }

    if ($resourceClass === Event::class) {
      $alias = $queryNameGenerator->generateJoinAlias('project');
      $aliasParticipations = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.project', $alias)
        ->join($alias . '.participations', $aliasParticipations)
        ->andWhere($aliasParticipations . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }

    if ($resourceClass === Project::class) {
      $alias = $queryNameGenerator->generateJoinAlias('participations');
      $rootAlias = $queryBuilder->getRootAliases()[0];
      $queryBuilder
        ->join($rootAlias . '.participations', $alias)
        ->andWhere($alias . '.user = :user')
        ->setParameter('user', $this->security->getUser());
    }
  }









  // protected function filterByUser(string $rootAlias, \Doctrine\ORM\QueryBuilder $queryBuilder)
  // {
  //     $rootAlias = $queryBuilder->getRootAliases()[0];
  //     $queryBuilder
  //         ->andWhere($rootAlias . '.user = :user')
  //         ->setParameter('user', $this->security->getUser());
  // }
}
