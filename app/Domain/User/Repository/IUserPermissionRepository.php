<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserPermission;

interface IUserPermissionRepository
{
    public function add(UserPermission $userPermission): void;
    public function update(UserPermission $userPermission): void;
    public function remove(UserPermission $userPermission): void;
}
