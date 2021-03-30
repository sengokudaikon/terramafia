<?php

namespace App\Event;

use DateTimeImmutable;

interface IPasswordResetTokenCreatedEvent
{
    /**
     * Возвращает идентификатор пользователя.
     *
     * @return int
     */
    public function getUserId(): int;

    /**
     * Возвращает токен.
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Возвращает время создания токена.
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable;
}
