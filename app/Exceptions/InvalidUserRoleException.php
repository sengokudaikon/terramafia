<?php

namespace App\Exceptions;

use App\Helpers\Response;
use InvalidArgumentException;

class InvalidUserRoleException extends InvalidArgumentException
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct(__('users.role.invalid'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
