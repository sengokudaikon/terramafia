<?php

namespace App\Exceptions;

use App\Helpers\Response;
use RuntimeException;

class InvalidCredentialsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.login.invalidCredentials'),
            Response::HTTP_UNAUTHORIZED
        );
    }

}
