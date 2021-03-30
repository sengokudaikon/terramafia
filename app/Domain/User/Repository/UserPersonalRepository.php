<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPersonal;

class UserPersonalRepository extends BaseRepository
{
    public function add(UserPersonal $userPersonal): void
    {
        $this->getEntityManager()->persist($userPersonal);
        $this->getEntityManager()->flush($userPersonal);
    }

    public function update(UserPersonal $userPersonal): void
    {
        $this->getEntityManager()->persist($userPersonal);
        $this->getEntityManager()->flush($userPersonal);
    }

    public function remove(UserPersonal $userPersonal): void
    {
        $this->getEntityManager()->remove($userPersonal);
        $this->getEntityManager()->flush($userPersonal);
    }
}
