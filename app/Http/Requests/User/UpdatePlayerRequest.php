<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

/**
 * Класс валидации запроса обновления профиля игрока.
 *
 * @OA\Schema(
 *     title="Запрос обновления профиля пользователя",
 *     @OA\Property(
 *          property="playerName",
 *          title="Имя",
 *          type="string",
 *
 *     )
 * )
 * @package App\Http\Requests\User
 */
class UpdatePlayerRequest extends FormRequest
{
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'playerName' => 'bail|required|string'
        ];
    }
}
