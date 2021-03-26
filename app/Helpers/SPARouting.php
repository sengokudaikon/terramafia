<?php

namespace App\Helpers;

/**
 * Вспомогательный класс для роутинга SPA приложения.
 *
 * @package App\Helpers
 */
class SPARouting
{
    /**
     * @var string URL для сброса пароля аккаунта.
     */
    public const FRONTEND_RESET_PASSWORD_URL = '/reset_password';

    /**
     * @var string URL для подтверждения email.
     */
    public const FRONTEND_CONFIRM_EMAIL_URL = '/confirm_email';

    /**
     * @var string URL списка проектов.
     */
    public const FRONTEND_LIST_PROJECTS = '/projects';

    /**
     * @var string URL списка задач
     */
    public const FRONTEND_BOARD = '/board';

    /**
     * @var string URL команды.
     */
    public const FRONTEND_TEAM = '/team';

    public const FRONTEND_VERIFY_REQUESTS = '/verify-requests/profile';

    /**
     * Возвращает URL для заданного раздела frontend приложения.
     *
     * @param string $url
     * @param array $params
     * @param string|null $serviceUrl
     * @return string
     */
    public static function makeFrontendUrl(string $url, array $params = [], ?string $serviceUrl = null): string
    {
        $appUrl = $serviceUrl ?? config('app.frontend_url');

        if ($appUrl[mb_strlen($appUrl) - 1] !== '/') {
            $appUrl[mb_strlen($appUrl)] = '/';
        }

        if ($url[0] === '/') {
            $url = mb_substr($url, 1);
        }

        $queryString = $params ? '?' . http_build_query($params) : '';

        return (string) $appUrl . $url . $queryString;
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function makeAuthUrl(string $url, array $params = []): string
    {
        $appUrl = config('app.auth_url');

        if ($appUrl[mb_strlen($appUrl) - 1] !== '/') {
            $appUrl[mb_strlen($appUrl)] = '/';
        }

        if ($url[0] === '/') {
            $url = mb_substr($url, 1);
        }

        $queryString = $params ? '?' . http_build_query($params) : '';

        return (string) $appUrl . $url . $queryString;
    }
}
