<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

/**
 * Класс валидации запроса для установки нового пароля пользователя.
 *
 * @OA\Schema(
 *     title="Запрос на редактирование пароля.",
 *     type="object",
 *     required={"token", "password"},
 *     @OA\Property(
 *          property="token",
 *          type="string",
 *          title="Токен сброса пароля."
 *     ),
 *     @OA\Property(
 *          property="password",
 *          type="string",
 *          title="Новый пароль.",
 *          minLength=5
 *     )
 * )
 * @package App\Http\Requests\User
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'token' => 'required|string',
            'password' => 'required|min:5'
        ];
    }
}
