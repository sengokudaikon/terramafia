<?php

namespace App\Service\User\Social;

use App\Domain\User\Entity\VO\SocialProvider;
use Laravel\Socialite\Facades\Socialite;
use RuntimeException;

/**
 * Класс управления авторизацией через социальный аккаунт.
 *
 * @package App\Service\Social
 */
class SocialAccountManager
{
    /**
     * @var string Название провайдера.
     */
    private string $provider;

    /**
     * Конструктор класса управления авторизацией через социальный аккаунт.
     *
     * @param string $provider
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct(string $provider)
    {
        $this->provider = $provider;
        $this->validateProvider();
    }

    public function getAccount(): SocialAccount
    {
        $provider = Socialite::driver($this->provider)->stateless()->user();
        $socialAccountClassName = $this->makeSocialAccountClassName($this->provider);

        return new $socialAccountClassName($provider, $this->provider);
    }

    /**
     * Валидация провайдера авторизации.
     *
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     */
    public function validateProvider(): void
    {
        SocialProvider::validateProvider($this->provider);

        $socialAccountClassName = $this->makeSocialAccountClassName($this->provider);

        if (!class_exists($socialAccountClassName)) {
            throw new RuntimeException(sprintf('Class %s does not exist.', $socialAccountClassName));
        }
    }

    /**
     * Генерирует название класса обработчика социального аккаунта пользователя.
     *
     * @param string $provider
     * @return string
     */
    public function makeSocialAccountClassName(string $provider): string
    {
        return __NAMESPACE__ . '\\' . ucfirst($provider) . 'SocialAccount';
    }
}
