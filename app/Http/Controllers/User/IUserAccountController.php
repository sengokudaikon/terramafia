<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\AddPersonalRequest;
use App\Http\Requests\User\ChangeEmailConfirmationRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdatePlayerRequest;
use App\Service\User\EmailConfirmationService;
use App\Service\User\UserPersonalDataService;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

interface IUserAccountController
{
    /**
     * Получение данных текущего пользователя.
     *
     * @OA\Get(
     *     path="/api/v1/users/me",
     *     tags={"Account"},
     *     summary="Персональные данные текущего пользователя.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *          response="200",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource"),
     *          description="Персональные данные.",
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация."
     *     )
     * )
     * @return JsonResponse
     */
    public function getMe(): JsonResponse;


    /**
     * @OA\Put(
     *     path="/api/v1/users/me",
     *     tags={"Account"},
     *     summary="Запрос обновит профиль пользователя",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdatePlayerRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Возвращает обновленного пользователя",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     *
     * @param UserService         $userService
     * @param UpdatePlayerRequest $request
     *
     * @return JsonResponse
     */
    public function updatePlayer(UserService $userService, UpdatePlayerRequest $request): JsonResponse;


    /**
     * Подтверждение email пользователя по токену.
     *
     * @OA\Post(
     *     path="/api/v1/users/me/email/confirmation",
     *     tags={"Account"},
     *     summary="Подтверждение email пользователя по токену",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/ChangeEmailConfirmationRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Email успешно подтвержден",
     *          @OA\JsonContent(ref="#/components/schemas/RespondWithToken"),
     *          @OA\Header(
     *              header="Set-Cookie",
     *              description="Установка ACCESS_TOKEN cookie",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token=***; Path=/; HttpOnly"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     * @param ChangeEmailConfirmationRequest $request
     * @param EmailConfirmationService       $emailConfirmationService
     *
     * @return JsonResponse
     */
    public function confirmEmail(ChangeEmailConfirmationRequest $request, EmailConfirmationService $emailConfirmationService): JsonResponse;

    /**
     * Изменяет email пользователя.
     *
     * @OA\Put(
     *     path="/api/v1/users/me/email",
     *     tags={"Account"},
     *     summary="Изменение email авторизованного пользователя",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/ChangeEmailRequest")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="Email успешно изменен"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Ошибка валидации или пароль введен неверно"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     * @param ChangeEmailRequest $request
     * @param UserService        $userService
     *
     * @return JsonResponse
     */
    public function changeEmail(ChangeEmailRequest $request, UserService $userService): JsonResponse;

    /**
     * Изменяет пароль пользоваетеля.
     *
     * @OA\Post(
     *     path="/api/v1/users/me/password",
     *     tags={"Account"},
     *     summary="Изменение пароля авторизованного пользователя",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/ChangePasswordRequest")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="Пароль успешно изменен"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Ошибка валидации или пароль введен не верно"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     * @param ChangePasswordRequest $request
     * @param UserService           $userService
     *
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request, UserService $userService): JsonResponse;

    /**
     * Добавление персональных данных пользователя.
     *
     * @OA\Post(
     *     path="/api/v1/users/me",
     *     tags={"Account"},
     *     summary="Запрос добавления  персональных данных пользователя",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/AddPersonalRequest")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="Персональные данные успешно добавлены."
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация."
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Ошибка валидации."
     *     )
     * )
     *
     * @param AddPersonalRequest      $request
     * @param UserService             $userService
     * @param UserPersonalDataService $personalDataService
     *
     * @return JsonResponse
     */
    public function addPersonalInfo(AddPersonalRequest $request, UserService $userService, UserPersonalDataService $personalDataService): JsonResponse;
}
