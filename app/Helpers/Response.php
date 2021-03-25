<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as Code;

/**
 * Class Response
 *
 * @package App\Helpers
 */
class Response
{
    /**
     * @var int Ответ "Успешно".
     */
    public const HTTP_SUCCESS = 200;

    /**
     * @var int "Создано". Запрос успешно выполнен и в результате был создан ресурс.
     */
    public const HTTP_CREATED = 201;

    /**
     * @var int "Нет содержимого". Нет содержимого для ответа на запрос.
     */
    public const HTTP_NO_CONTENT = 204;

    /**
     * @var int "Плохой запрос".
     * Этот ответ означает, что сервер не понимает запрос из-за неверного синтаксиса.
     */
    public const HTTP_BAD_REQUEST = 400;

    /**
     * @var int "Неавторизовано".
     * Для получения запрашиваемого ответа нужна аутентификация.
     */
    public const HTTP_UNAUTHORIZED = 401;

    /**
     * @var int "Запрещено". У клиента нет прав доступа к содержимому,
     * поэтому сервер отказывается дать надлежащий ответ.
     */
    public const HTTP_FORBIDDEN = 403;

    /**
     * @var int "Не найден". Сервер не может найти запрашиваемый ресурс.
     */
    public const HTTP_NOT_FOUND = 404;

    /**
     * @var int Этот ответ отсылается, когда запрос конфликтует
     * с текущим состоянием сервера.
     */
    public const HTTP_CONFLICT = 409;

    /**
     * @var int Cинтаксис запроса является правильным,
     * но серверу не удалось обработать инструкции содержимого.
     */
    public const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var int "Внутренняя ошибка сервера".
     * Сервер столкнулся с ситуацией, которую он не знает как обработать.
     */
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * Успешный ответ API.
     *
     * @param string|null $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public static function successResponse(
        ?string $message = null,
        $data = [],
        int $code = self::HTTP_SUCCESS
    ): JsonResponse {
        $result = array_merge(
            $message ? ['message' => __($message)] : [],
            $data
        );

        return response()->json($result, $code);
    }

    /**
     * Ответ API с ошибкой.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function errorResponse(string $message, int $code = self::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json(['message' => __($message)], $code ?: Code::HTTP_BAD_GATEWAY);
    }
}
