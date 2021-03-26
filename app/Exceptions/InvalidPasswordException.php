<?php

namespace App\Exceptions;

use App\Helpers\Response;
use InvalidArgumentException;

class InvalidPasswordException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(__('users.password.invalid'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
