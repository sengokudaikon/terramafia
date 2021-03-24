<?php

namespace App\Domain\User\Entity\VO;

use App\Exceptions\InvalidUserRoleException;

class Role
{
    public const PLAYER = 'player';

    public const MODERATOR = 'moderator';

    public const ADMIN = 'admin';

    /**
     * @var string Значение.
     */
    private string $value;

    public static function getValues(): array
    {
        return [
            self::PLAYER,
            self::MODERATOR,
            self::ADMIN
        ];
    }

    public function __construct(string $value)
    {
        if (!in_array($value, self::getValues())) {
            throw new InvalidUserRoleException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isPlayer(): bool
    {
        return $this->value === self::PLAYER;
    }

    public function isModerator(): bool
    {
        return $this->value === self::MODERATOR;
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }
}
