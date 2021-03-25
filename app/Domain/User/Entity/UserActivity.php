<?php

namespace App\Domain\User\Entity;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Embeddable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @package App\Domain\User\Entity
 */
class UserActivity
{
    /**
     * @var DateTimeImmutable Время создания.
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    public DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable Время обновления.
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime_immutable")
     */
    public DateTimeImmutable $updatedAt;

    /**
     * @var DateTimeImmutable Время удаления.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    public DateTimeImmutable $deletedAt;

    /**
     * @var bool Флаг состояния - пользователь подтвержден (не является роботом).
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $isConfirmed;

    /**
     * @var bool Флаг состояния - email подтвержден.
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $emailVerified = false;

    /**
     * @var bool Флаг состояния - пользователь заблокирован.
     */
    private bool $isBanned;

    public function __construct()
    {
        $this->isConfirmed = false;
        $this->isBanned = false;

        $this->onRegistered();
    }

    public function onRegistered(): void
    {
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('utc'));
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    protected function onUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable('now', new DateTimeZone('utc'));
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDeletedAt(): DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public function block(): void
    {
        if (!$this->isBanned) {
            $this->isBanned = true;
        }
    }

    public function unblock(): void
    {
        if ($this->isBanned) {
            $this->isBanned = false;
        }
    }

    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function confirm(): void
    {
        if (!$this->isConfirmed) {
            $this->isConfirmed = true;
            $this->onUpdated();
        }
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function verifyEmail(): void
    {
        if (!$this->emailVerified) {
            $this->emailVerified = true;
            $this->onUpdated();
        }
    }
}
