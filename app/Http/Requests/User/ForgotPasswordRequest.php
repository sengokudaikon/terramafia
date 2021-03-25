<?php

namespace App\Http\Requests\User;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс валидации запроса восстановления пароля аккаунта пользователя.
 *
 * @OA\Schema(
 *     schema="ForgotPasswordRequest",
 *     title="Запрос на восстановление пароля.",
 *     type="object",
 *     required={"email"},
 *     @OA\Property(
 *          property="email",
 *          description="Email пользователя.",
 *          type="string",
 *          example="admin@terra.ru"
 *     )
 * )
 * @package App\Http\Requests\User
 */
class ForgotPasswordRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Правила валидации.
     *
     * @return array|string[]
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|email'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters()
    {
        return [
            'email' => 'trim'
        ];
    }
}
