<?php

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\VO\SocialProvider;
use Doctrine\ORM\Mapping as ORM;

/**
 * Социальный аккаунт пользователя.
 *
 * @ORM\Entity(
 *     repositoryClass="App\Domain\User\Repository\UserSocialAccountRepository"
 * )
 * @ORM\Table(name="users_user_social_account")
 *
 * @package App\Domain\User\Entity
 */
class UserSocialAccount
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
     * @var User Пользователь (владелец аккаунта).
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\User\Entity\User",
     *     inversedBy="socialAccounts"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private User $user;

    /**
     * @var SocialProvider Провайдер аутентификации через социальный аккаунт.
     *
     * @ORM\Embedded(class="App\Domain\User\Entity\VO\SocialProvider")
     */
    private SocialProvider $provider;

    /**
     * @var bool Признак, что аккаунт используется для авторизации.
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $credential = false;

    /**
     * Конструктор социального аккаунта.
     *
     * @param User $user
     * @param string $providerName
     * @param string $providerAccountId
     * @throws \InvalidArgumentException
     */
    public function __construct(User $user, string $providerName, string $providerAccountId)
    {
        $this->user = $user;
        $this->provider = new SocialProvider($providerName, $providerAccountId);
    }

    /**
     * @param bool $credential
     *
     * @return UserSocialAccount
     * @see UserSocialAccount::$credential
     */
    public function setCredential(bool $credential): self
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * @return int
     * @see UserSocialAccount::$id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     * @see UserSocialAccount::$user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return SocialProvider
     * @see UserSocialAccount::$provider
     */
    public function getProvider(): SocialProvider
    {
        return $this->provider;
    }

    /**
     * @return bool
     * @see UserSocialAccount::$credential
     */
    public function isCredential(): bool
    {
        return $this->credential;
    }
}
