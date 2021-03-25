<?php

namespace App\Event;

class UserCreatedEvent implements IUserCreatedEvent
{
    /**
     * @var int Идентификатор пользователя.
     */
    private int $userId;

    /**
     * Конструктор события создания пользователя.
     *
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     * @see UserCreatedEvent::$userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
