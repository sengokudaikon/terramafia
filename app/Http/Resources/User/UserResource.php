<?php

namespace App\Http\Resources\User;
/**
 * Класс JSON ответа с данными пользователя.
 *
 * @OA\Schema(
 *     title="Пользователь",
 *     @OA\Property(
 *          property="id",
 *          title="Идентификатор пользователя.",
 *          type="string",
 *          example="GWOIOISAmWFS"
 *     ),
 *     @OA\Property(
 *          property="playerName",
 *          title="Игровое имя пользователя.",
 *          type="string",
 *          maxLength=32,
 *          example="Лоуренс"
 *     ),
 *     @OA\Property(
 *          property="email",
 *          title="Email пользователя.",
 *          type="string",
 *          maxLength=129,
 *          example="example@gmail.com"
 *     ),
 *      @OA\Property(
 *          property="firstName",
 *          title="Имя.",
 *          type="string",
 *          maxLength=120,
 *          example="Даниил."
 *     ),
 *     @OA\Property(
 *          property="lastName",
 *          title="Фамилия.",
 *          type="string",
 *          maxLength=120,
 *          example="Заврин."
 *     ),
 *     @OA\Property(
 *          property="patronymic",
 *          title="Отчество.",
 *          type="string",
 *          maxLength=120,
 *          example="Алексеевич."
 *     ),
 *     @OA\Property(
 *          property="birthday",
 *          title="День рождения.",
 *          type="date",
 *          example="1998-05-22"
 *     ),
 *     @OA\Property(
 *          property="gender",
 *          title="Пол",
 *          type="string",
 *          enum={"male", "female", "other"},
 *          example="male"
 *     )
 * )
 * @package App\Http\Resources\User
 */
class UserResource
{
}
