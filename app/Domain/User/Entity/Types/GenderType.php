<?php

namespace App\Domain\User\Entity\Types;

use App\Domain\User\Entity\VO\Gender;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class GenderType extends Type
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return Gender::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Gender($value);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Gender) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid conversion value "%s", should be "%s"',
                    is_object($value) ? get_class($value) : gettype($value),
                    Gender::class
                )
            );
        }

        return (string) $value;
    }
}
