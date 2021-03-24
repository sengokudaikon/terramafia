<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface IReadUserRepository
{
    /**
     * Находит пользователя по id.
     *
     * @param string $userId
     * @return User
     */
    public function findUserByUuid(string $userId): User;

    /**
     * Находит пользователя по его email.
     *
     * @param string $userEmail - email пользователя.
     * @return User - найденный пользователь.
     */
    public function findUserByEmail(string $userEmail): User;

    /**
     * Находит всех юзеров.
     *
     * @return User[]
     */
    public function findAllUsers(): array;

    /**
     * @param array $ids
     * @return User[]
     */
    public function findUsersByIds(array $ids): array;
}
