<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\AuthenticateUserRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Service\User\PasswordReminderService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

interface IUserAuthController
{
    /**
     * Аутентификация пользователя.
     *
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary="Аутентификация пользователя по email.",
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/AuthenticateUserRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Аутентификация прошла успешно.",
     *          @OA\JsonContent(ref="#/components/schemas/RespondWithToken"),
     *          @OA\Header(
     *              header="Set-Cookie",
     *              description="Установка ACCESS_TOKEN cookie.",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token=***; Path=/; HttpOnly"
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Ошибка валидации."
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Пользователь не найден."
     *     )
     * )
     * @param UserService             $userService
     * @param AuthenticateUserRequest $request
     *
     * @return JsonResponse
     */
    public function login(UserService $userService, AuthenticateUserRequest $request): JsonResponse;

    /**
     * Разлогинить пользователя.
     *
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Auth"},
     *     summary="Инвалидация токена.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *          response="200",
     *          description="Токен инвалидирован.",
     *          @OA\Header(
     *              header="Set-Cookie",
     *              description="Удаление ACCESS_TOKEN cookie.",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token=deleted; Path=/; HttpOnly"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация."
     *     )
     * )
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function logout(UserService $userService): JsonResponse;

    /**
     * Восстановление пароля.
     *
     * @OA\Post(
     *     path="/api/v1/auth/forgot",
     *     tags={"Auth"},
     *     summary="Восстановление пароля.",
     *     description="После запроса на email пользователя будет отправлена ссылка для восстановления пароля.",
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="На email отправлена ссылка для восстановления пароля."
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Пользователь не найден."
     *     )
     * )
     *
     * @param ForgotPasswordRequest   $request
     * @param PasswordReminderService $passwordReminderService
     *
     * @return JsonResponse
     */
    public function forgot(ForgotPasswordRequest $request, PasswordReminderService $passwordReminderService): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/v1/auth/resetPassword",
     *     tags={"Auth"},
     *     summary="Сброс и установка нового пароля пользователя.",
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Пароль успешно изменен."
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Код сброса пароля уже не действителен."
     *     )
     * )
     * @param ResetPasswordRequest $request
     * @param UserService          $userService
     *
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request, UserService $userService): JsonResponse;
}
