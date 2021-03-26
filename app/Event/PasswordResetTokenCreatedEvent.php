<?php

namespace App\Event;

use DateTimeImmutable;

class PasswordResetTokenCreatedEvent implements IPasswordResetTokenCreatedEvent
{
    /**
     * @var int Идентификатор пользователя.
     */
    private int $userId;

    /**
     * @var string Токен.
     */
    private string $token;

    /**
     * @var DateTimeImmutable Время создания токена.
     */
    private DateTimeImmutable $createdAt;

    /**
     * Конструктор события создания токена восстановления пароля пользователя.
     *
     * @param int $userId
     * @param string $token
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(int $userId, string $token, DateTimeImmutable $createdAt)
    {
        $this->userId = $userId;
        $this->token = $token;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     * @see PasswordResetTokenCreatedEvent::$userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     * @see PasswordResetTokenCreatedEvent::$token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return DateTimeImmutable
     * @see PasswordResetTokenCreatedEvent::$createdAt
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
