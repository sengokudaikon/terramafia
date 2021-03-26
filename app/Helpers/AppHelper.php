<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

/**
 * Вспомогательный класс для приложения.
 *
 * @package App\Helpers
 */
class AppHelper
{
    /**
     * Проверяет, используется ли dev окружение.
     *
     * @return bool
     */
    public static function isDevEnvironment(): bool
    {
        return App::environment('dev');
    }

    /**
     * Проверяет, используется ли test окружение.
     *
     * @return bool
     */
    public static function isTestEnvironment(): bool
    {
        return App::environment('test');
    }

    /**
     * Проверяет, используется ли production окружение.
     *
     * @return bool
     */
    public static function isProdEnvironment(): bool
    {
        return App::environment('production');
    }
}
