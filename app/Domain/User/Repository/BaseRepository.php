<?php

namespace App\Domain\User\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class BaseRepository
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    protected function find(string $id, ?string $entityClass = null): ?object
    {
        return $this->entityManager->find($entityClass, $id);
    }

    protected function select(string $entityClass, string $alias): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()->from($entityClass, $alias)->select($alias);
    }

    protected function from(string $entity, string $alias, string $indexBy = null): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()->from($entity, $alias, $indexBy);
    }
}
