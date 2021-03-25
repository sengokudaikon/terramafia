<?php

namespace App\Http\Requests\User;
use App\Http\Requests\FormRequest;

/**
 * Клас валидации запроса подтверждения нового email пользователя.
 *
 * @OA\Schema(
 *     title="Запрос на подтверждение нового email",
 *     required={"confirmationToken"},
 *     @OA\Property(
 *          property="confirmationToken",
 *          description="Токен",
 *          type="string"
 *     )
 * )
 * @package App\Http\Requests\User
 */
class ChangeEmailConfirmationRequest extends FormRequest
{
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'confirmationToken' => 'required'
        ];
    }
}
