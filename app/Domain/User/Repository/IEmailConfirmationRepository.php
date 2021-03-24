<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\EmailConfirmationToken;
use App\Domain\User\Entity\User;

interface IEmailConfirmationRepository
{
    public function add(EmailConfirmationToken $emailConfirmationToken): void;
    public function findByToken(string $token): EmailConfirmationToken;
    public function deleteByUser(User $user): void;
    public function remove(EmailConfirmationToken $emailConfirmationToken): void;
}
