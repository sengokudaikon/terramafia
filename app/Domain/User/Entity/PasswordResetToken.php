<?php

namespace App\Domain\User\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

class PasswordResetToken
{
    /**
     * @var int Идентификатор.
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var User Пользователь.
     *
     * @ORM\ManyToOne(
     *     targetEntity="Modules\Users\Entities\User"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private User $user;

    /**
     * @var string Токен
     *
     * @ORM\Column(
     *     type="string",
     *     length=100
     * )
     */
    private string $token;

    /**
     * @var DateTimeImmutable Время создания.
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * Конструктор сущности токена сброса пароля пользователя.
     *
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @param string $token
     * @return self
     * @see PasswordResetToken::$token
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return int
     * @see PasswordResetToken::$id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     * @see PasswordResetToken::$user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     * @see PasswordResetToken::$token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return DateTimeImmutable
     * @see PasswordResetToken::$createdAt
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
