<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\PasswordResetToken;
use App\Domain\User\Entity\User;

interface IPasswordResetTokenRepository
{
    public function add(PasswordResetToken $passwordResetToken): void;
    public function findByToken(string $token): PasswordResetToken;
    public function deleteByUser(User $user): void;
    public function findByUser(User $user): ?PasswordResetToken;
}
