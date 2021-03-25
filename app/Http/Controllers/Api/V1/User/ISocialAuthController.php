<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Service\User\SocialAccountService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface ISocialAuthController
{
    /**
     * Возвращает ссылку для авторизации через социальный аккаунт.
     *
     * @OA\Get(
     *     path="/api/v1/auth/login/{provider}",
     *     tags={"Auth"},
     *     summary="Запрос получения URL для аутентификации через социальный аккаунт",
     *     @OA\Parameter(
     *          name="provider",
     *          in="path",
     *          required=true,
     *          description="Идентификатор провайдера",
     *          @OA\Schema(ref="#/components/schemas/SocialProviderList")
     *     ),
     *     @OA\Parameter(
     *          name="state",
     *          in="query",
     *          description="Значение параметра вернётся в обратной ссылке после авторизации у провайдера.",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Получена ссылка для переадресации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="redirectUrl",
     *                  type="string",
     *                  example="https://example.com/"
     *              )
     *          )
     *     )
     * )
     * @param Request              $request
     * @param SocialAccountService $socialAccountService
     *
     * @return JsonResponse
     */
    public function getAuthUrlForExternalService(Request $request, SocialAccountService $socialAccountService): JsonResponse;

    /**
     * Обрабатывает ответ пришедший от провайдера аутентификации через соц. аккаунт.
     *
     * @OA\Post(
     *     path="/api/v1/auth/login/{provider}/callback",
     *     tags={"Auth"},
     *     summary="Обработка ответа, пришедшего от провайдера.",
     *     @OA\Parameter(
     *          name="provider",
     *          in="path",
     *          required=true,
     *          description="Идентификатор провайдера",
     *          @OA\Schema(ref="#/components/schemas/SocialProviderList")
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="code",
     *                  description="Код авторизации через социальный аккаунт",
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Выполнен вход через социальный аккаунт",
     *          @OA\JsonContent(ref="#/components/schemas/RespondWithToken"),
     *          @OA\Header(
     *              header="Set-Cookie",
     *              description="Установка ACCESS_TOKEN cookie.",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token=***; Path=/; HttpOnly"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Токен не действителен."
     *     )
     * )
     * @param string      $provider
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function handleProviderCallback(string $provider, UserService $userService): JsonResponse;
}
