<?php

namespace App\Service\User\Social;

use Laravel\Socialite\AbstractUser;

/**
 * Абстрактный класс социального аккаунта пользователя.
 *
 * @package App\Service\Social
 */
abstract class SocialAccount
{
    /**
     * @var AbstractUser Внешний аккаунт пользователя.
     */
    private AbstractUser $user;

    /**
     * @var string Название провайдера.
     */
    private string $provider;

    /**
     * Конструктор абстрактного класса социального аккаунта пользователя.
     *
     * @param AbstractUser $user
     * @param string $provider
     */
    public function __construct(AbstractUser $user, string $provider)
    {
        $this->user = $user;
        $this->provider = $provider;
    }

    /**
     * Возвращает идентификатор соц. аккаунта пользователя.
     *
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->user->getId();
    }

    /**
     * Возвращает имя пользователя.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->user->getName() ?: null;
    }

    /**
     * Возвращает email пользователя.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->user->getEmail() ?: null;
    }

    /**
     * @return string
     * @see SocialAccount::$provider
     */
    public function getProvider(): string
    {
        return $this->provider;
    }
}
