<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPersonal;

interface IUserPersonalRepository
{
    public function add(UserPersonal $userPersonal): void;
    public function update(UserPersonal $userPersonal): void;
    public function remove(UserPersonal $userPersonal): void;
}
