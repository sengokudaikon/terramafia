<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPermission;

class UserPermissionRepository extends BaseRepository implements IUserPermissionRepository
{
    public function add(UserPermission $userPermission): void
    {
        $this->entityManager->persist($userPermission);
        $this->entityManager->flush($userPermission);
    }

    public function update(UserPermission $userPermission): void
    {
        $this->entityManager->persist($userPermission);
        $this->entityManager->flush($userPermission);
    }

    public function remove(UserPermission $userPermission): void
    {
        $this->entityManager->remove($userPermission);
        $this->entityManager->flush($userPermission);
    }

    public function findAll(): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder();

        $queryBuilder->select('permission')
            ->from(UserPermission::class, 'permission');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
