<?php

namespace App\Http\Requests\User;
use App\Domain\User\Entity\VO\Gender;
use App\Http\Requests\FormRequest;

/**
 * Класс валидации запроса на добавление персональных данных игрока.
 *
 * @OA\Schema(
 *     title="Запрос на добавление персональных данных игрока",
 *     type="object",
 *     required={"fullName", "gender", "specialisationId"},
 *     @OA\Property(
 *          property="firstName",
 *          type="string",
 *          title="Имя пользователя(игрока)",
 *          example="Федор",
 *          maxLength=255,
 *          minLength=1
 *     ),
 *     @OA\Property(
 *          property="lastName",
 *          type="string",
 *          title="Фамилия пользователя(игрока)",
 *          example="Панасенко",
 *          maxLength=255,
 *          minLength=1
 *     ),
 *     @OA\Property(
 *          property="patronymic",
 *          type="string",
 *          title="Отчество пользователя(игрока)",
 *          example="Остапович",
 *          maxLength=255,
 *          minLength=1
 *     ),
 *     @OA\Property(
 *          property="birthdate",
 *          type="string",
 *          title="Дата рождения",
 *          format="date",
 *          example="1981-10-10",
 *          nullable=true
 *     ),
 *     @OA\Property(
 *          property="gender",
 *          type="string",
 *          title="Пол",
 *          enum={"male", "female", "other"},
 *          example="male",
 *     )
 * )
 * @package App\Http\Requests\User
 */
class AddPersonalRequest extends FormRequest
{
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'firstName' => 'bail|required|string|min:1',
            'lastName' => 'bail|required|string|min:1',
            'patronymic' => 'bail|required|string|min:1',
            'birthdate' => 'bail|required|date',
            'gender' => 'bail|required|string|in:' . implode(',', Gender::getValues()),
        ];
    }
}
