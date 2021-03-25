<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

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
     * @return string[]
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
