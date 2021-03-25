<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

/**
 * Класс валидации запроса редактирования пароля.
 *
 * @OA\Schema(
 *     title="Запрос изменения пароля",
 *     required={"currentPassword", "newPassword"},
 *     @OA\Property(
 *          property="currentPassword",
 *          title="Текущий пароль",
 *          type="string",
 *          example="123456"
 *     ),
 *     @OA\Property(
 *          property="newPassword",
 *          title="Новый пароль",
 *          type="string",
 *          example="123456",
 *          minLength=5
 *     )
 * )
 * @package App\Http\Requests\User
 */
class ChangePasswordRequest extends FormRequest
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
            'newPassword' => 'bail|required|string|min:5',
        ];
    }
}
