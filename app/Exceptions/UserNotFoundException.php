<?php

namespace App\Exceptions;

class UserNotFoundException extends CEntityNotFoundException
{
    public function getEntityName(): string
    {
        return 'User';
    }
}
