<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface IUserRepository extends IReadUserRepository
{
    public function add(User $user);

    public function update(User $user);

    public function remove(User $user);
}
