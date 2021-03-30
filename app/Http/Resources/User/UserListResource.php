<?php

namespace App\Http\Resources\User;
/**
 * Список игроков.
 * @OA\Schema(
 *     title="Игроки",
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/UserResource")
 *     )
 * )
 * @package App\Http\Resources\User
 */
class UserListResource
{
}
