<?php

namespace App\VO;

use InvalidArgumentException;

/**
 * Провайдер социальной сети.
 *
 * @property string $name
 * @property string $accountId
 * @package App\VO
 */
class SocialProvider
{
    /**
     * @var string Гугл.
     */
    public const GOOGLE = 'google';

    /**
     * @var string Вконтакте.
     */
    public const VKONTAKTE = 'vkontakte';

    /**
     * @var string Фейсбук.
     */
    public const FACEBOOK = 'facebook';

    /**
     * @var array Допустимые провайдеры.
     */
    public const PROVIDERS = [
        self::GOOGLE,
        self::VKONTAKTE,
        self::FACEBOOK
    ];

    /**
     * Конструктор провайдера для аутентификации через соц. аккаунт.
     *
     * @param string $name
     * @param string $accountId
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, string $accountId)
    {
        self::validateProvider($name);

        $this->name = $name;
        $this->accountId = $accountId;
    }

    /**
     * Валидирует провайдера.
     *
     * @param string $provider
     * @throws InvalidArgumentException
     */
    public static function validateProvider(string $provider): void
    {
        if (!in_array($provider, self::PROVIDERS, true)) {
            throw new InvalidArgumentException(
                sprintf('Провайдер %s запрещен для аутентификации.', $provider)
            );
        }
    }

    /**
     * @return string
     * @see SocialProvider::$name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     * @see SocialProvider::$accountId
     */
    public function accountId(): string
    {
        return $this->accountId;
    }
}
