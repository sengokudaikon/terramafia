<?php

namespace App\Exceptions;

use App\Helpers\Response;
use RuntimeException;

class EmailAlreadyExistsException extends RuntimeException
{
    public function __construct(string $email)
    {
        parent::__construct(
            sprintf(__('users.email.alreadyExists'), $email),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
