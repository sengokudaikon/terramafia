<?php

namespace App\Domain\User\Entity\Types;

use App\Domain\User\Entity\VO\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class RoleType extends Type
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return Role::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Role($value);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Role) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid conversion value "%s", should be "%s"',
                    is_object($value) ? get_class($value) : gettype($value),
                    Role::class
                )
            );
        }

        return (string) $value;
    }
}
