<?php

namespace App\VO;

use http\Exception\InvalidArgumentException;

/**
 * Class Gender
 * @property string|null $value
 * @package App\VO
 */
class Gender
{
    public const MALE = 'male';
    public const FEMALE = 'female';
    public const OTHER = 'other';

    public static function getValues(): array
    {
        return [
            self::MALE,
            self::FEMALE,
            self::OTHER
        ];
    }

    public function __construct(?string $value)
    {
        if ($value && !in_array($value, self::getValues())) {
            throw new InvalidArgumentException('Неверное значение пола пользователя');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
