<?php

namespace App\Http\Controllers;

use App\Domain\User\Entity\User;
use App\Helpers\Response;
use App\Service\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Основной класс контроллеров.
 *
 * @package App\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Успешный ответ API.
     *
     * @param string|null $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(
        ?string $message = null,
        $data = [],
        int $code = Response::HTTP_SUCCESS
    ): JsonResponse {
        return Response::successResponse($message, $data, $code);
    }

    /**
     * Успешный ответ с data данными.
     *
     * @param array $data
     * @param array $additional
     * @return JsonResponse
     */
    protected function dataResponse(array $data = [], array $additional = []): JsonResponse
    {
        return Response::successResponse(
            null,
            array_merge(['data' => $data], $additional),
            Response::HTTP_SUCCESS
        );
    }

    /**
     * Успешный ответ API с айди созданной сущности с кодом 201.
     *
     * @param string $uuid
     *
     * @return JsonResponse
     */
    protected function successResponseCreated(string $uuid): JsonResponse
    {
        return Response::successResponse(null, ['uuid' => $uuid], Response::HTTP_CREATED);
    }

    /**
     * Успешный ответ API без содержимого.
     *
     * @return JsonResponse
     */
    protected function successResponseWithoutContent(): JsonResponse
    {
        return Response::successResponse(null, [], Response::HTTP_NO_CONTENT);
    }

    /**
     * Ответ API с ошибкой.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return Response::errorResponse($message, $code);
    }

    /**
     * Ответ с токеном аутентификации.
     *
     * @param string $token
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, string $message)
    {
        /** @var User $user */
        $user = Auth::user();
        $ttl = Auth::factory()->getTTL(); // время жизни токена в минутах

        return $this->successResponse($message, [
            'token' => $token,
            'ttl' => $ttl,
            'uuid' => $user->getUuid()->toString()
        ])->withCookie('token', $token, $ttl);
    }
}
