<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth as BaseAuth;

class Auth extends BaseAuth
{
    /**
     * Возвращает UUID авторизованного пользователя.
     *
     * @return string|null
     */
    public static function uuid(): ?string
    {
        return self::check() ? self::user()->getExternalisedUuid() : null;
    }
}
