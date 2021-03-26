<?php

namespace App\Domain\User\Entity\VO;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Провайдер социальной сети.
 *
 * @ORM\Embeddable
 * @package App\Domain\User\Entity\VO
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
     * @var string Название провайдера.
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var string Идентификатор аккаунта внешнего сервиса.
     * @ORM\Column(type="string")
     */
    private string $accountId;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     * @see SocialProvider::$accountId
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }
}
