<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\UserSocialAccount;

interface IUserSocialAccountRepository
{
    public function add(UserSocialAccount $socialAccount): void;
    public function update(UserSocialAccount $socialAccount): void;
    public function remove(UserSocialAccount $socialAccount): void;
}
