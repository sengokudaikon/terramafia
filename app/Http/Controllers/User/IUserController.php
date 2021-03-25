<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\RegisterPlayerRequest;
use App\Service\User\UserService;
use Illuminate\Http\JsonResponse;

interface IUserController
{
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"User"},
     *     summary="Запрос вернет всех пользователей",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *          response="200",
     *          description="Список пользователей",
     *          @OA\JsonContent(ref="#/components/schemas/UserListResource")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     *
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function getPlayerList(UserService $userService): JsonResponse;

    /**
     * Регистрация пользователя.
     *
     * @OA\Post(
     *     path="/api/v1/users/register",
     *     tags={"User"},
     *     summary="Регистрация пользователя по email.",
     *     operationId="register",
     *     @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/RegisterPlayerRequest")
     *     ),
     *     @OA\Response(
     *          response="201",
     *          description="Регистрация прошла успешно.",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="uuid",
     *                  type="string",
     *                  example="SHdjwdfANJiSJdn"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Ошибка валидации."
     *     )
     * )
     * @param UserService           $userService
     * @param RegisterPlayerRequest $request
     *
     * @return JsonResponse
     */
    public function registerPlayer(UserService $userService, RegisterPlayerRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/users/{uuid}",
     *     tags={"User"},
     *     summary="Запрос вернет пользователя с данным id",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="Идентификатор пользователя",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Возвращает пользователя с данным id",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     *
     * @param string      $uuid
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function getPlayerByUuid(string $uuid, UserService $userService): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{uuid}",
     *     tags={"User"},
     *     summary="Запрос удалит пользователя с данным id",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="Идентификатор пользователя",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="Пользователь успешно удален"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Требуется авторизация"
     *     )
     * )
     *
     * @param string      $uuid
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function deletePlayer(string $uuid, UserService $userService): JsonResponse;
}
