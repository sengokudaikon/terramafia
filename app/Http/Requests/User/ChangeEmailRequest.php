<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

/**
 * Класс валидации запроса на изменение email.
 *
 * @OA\Schema(
 *     title="Запрос на изменение email",
 *     required={"currentPassword", "email"},
 *     @OA\Property(
 *          property="currentPassword",
 *          title="Текущий пароль",
 *          type="string",
 *          example="123456"
 *     ),
 *     @OA\Property(
 *          property="email",
 *          title="Электронный адрес",
 *          type="string",
 *          example="user@terra.ru"
 *     )
 * )
 * @package App\Http\Requests\User
 */
class ChangeEmailRequest extends FormRequest
{
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'currentPassword' => 'bail|required|string',
            'email' => 'bail|required|email',
        ];
    }
}
