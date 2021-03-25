<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * Класс валидации.
 *
 * @package App\Http\Requests
 */
class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => (new ValidationException($validator))->getMessage(),
                'errors' => (new ValidationException($validator))->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
