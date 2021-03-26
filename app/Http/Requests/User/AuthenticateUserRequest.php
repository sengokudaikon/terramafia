<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

/**
 * Класс валидации запроса для аутентификации пользователя.
 *
 * @OA\Schema(
 *     title="Запрос аутентификации пользователя.",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Property(
 *          property="email",
 *          description="Email.",
 *          type="string",
 *          example="admin@terra.ru"
 *     ),
 *     @OA\Property(
 *          property="password",
 *          description="Пароль пользователя.",
 *          type="string",
 *          example="123456",
 *          minLength=6
 *     ),
 *     @OA\Property(
 *          property="rememberMe",
 *          description="Флаг сохренения состояния сессии.",
 *          type="boolean",
 *          example="true"
 *     )
 * )
 * @package App\Http\Requests\User
 */
class AuthenticateUserRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Проверка валидации.
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'bail|required|string|email',
            'password' => 'bail|required|string|min:6'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            'email' => 'trim'
        ];
    }
}
