<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

/**
 * Класс валидации запроса для регистрации пользователя.
 *
 * @OA\Schema(
 *     title="Запрос регистрации игрока.",
 *     type="object",
 *     required={"email", "playerName", "password"},
 *     @OA\Property(
 *          property="email",
 *          description="Email.",
 *          type="string",
 *          example="test@terra.ru"
 *     ),
 *     @OA\Property(
 *          property="playerName",
 *          description="Внутриигровое имя.",
 *          type="string",
 *          example="Лоуренс"
 *     ),
 *     @OA\Property(
 *          property="password",
 *          description="Пароль.",
 *          type="string",
 *          example="123456"
 *     )
 * )
 * @package App\Http\Requests\User
 */
class RegisterPlayerRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Правила валидации.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|string|email|max:129',
            'password' => 'bail|required|min:5',
            'playerName' => 'bail|nullable|string|max:32'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters()
    {
        return [
            'playerName' => 'trim|strip_tags',
            'email' => 'trim'
        ];
    }
}
