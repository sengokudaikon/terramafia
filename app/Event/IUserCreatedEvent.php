<?php

namespace App\Event;

interface IUserCreatedEvent
{
    /**
     * Возвращает идентфикатор пользователя.
     *
     * @return int
     */
    public function getUserId(): int;
}
