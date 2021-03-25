<?php

namespace App\Domain\User\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Токен подтверждения email пользователя.
 *
 * @ORM\Entity(
 *     repositoryClass="App\Domain\User\Repository\EmailConfirmationRepository"
 * )
 * @ORM\Table(name="users_email_confirmation_token")
 * @package App\Domain\User\Entity
 */
class EmailConfirmationToken
{
    /**
     * @var int Идентификатор.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var User Пользователь.
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\User\Entity\User",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private User $user;

    /**
     * @var string Токен подтверждения.
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private string $token;

    /**
     * @var string Подтверждаемый email.
     *
     * @ORM\Column(type="string", length=129)
     */
    private string $email;

    /**
     * @var DateTimeImmutable Время создания.
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * Конструктор токена подтверждения email пользователя.
     *
     * @param User $user
     * @param string $token
     * @param string $email
     */
    public function __construct(User $user, string $token, string $email)
    {
        $this->user = $user;
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * @return int
     * @see EmailConfirmationToken::$id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     * @see EmailConfirmationToken::$user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     * @see EmailConfirmationToken::$token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     * @see EmailConfirmationToken::$email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return DateTimeImmutable
     * @see EmailConfirmationToken::$createdAt
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param string $email
     * @return self
     * @see EmailConfirmationToken::$email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
