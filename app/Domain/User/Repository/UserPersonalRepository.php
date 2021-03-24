<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPersonal;

class UserPersonalRepository extends BaseRepository implements IUserPersonalRepository
{
    public function add(UserPersonal $userPersonal): void
    {
        $this->entityManager->persist($userPersonal);
        $this->entityManager->flush($userPersonal);
    }

    public function update(UserPersonal $userPersonal): void
    {
        $this->entityManager->persist($userPersonal);
        $this->entityManager->flush($userPersonal);
    }

    public function remove(UserPersonal $userPersonal): void
    {
        $this->entityManager->remove($userPersonal);
        $this->entityManager->flush($userPersonal);
    }
}
