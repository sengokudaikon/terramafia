<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPermission;

class UserPermissionRepository extends BaseRepository
{
    public function add(UserPermission $userPermission): void
    {
        $this->getEntityManager()->persist($userPermission);
        $this->getEntityManager()->flush($userPermission);
    }

    public function update(UserPermission $userPermission): void
    {
        $this->getEntityManager()->persist($userPermission);
        $this->getEntityManager()->flush($userPermission);
    }

    public function remove(UserPermission $userPermission): void
    {
        $this->getEntityManager()->remove($userPermission);
        $this->getEntityManager()->flush($userPermission);
    }

    public function findAll(): array
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $queryBuilder->select('permission')
            ->from(UserPermission::class, 'permission');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
