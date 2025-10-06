<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseApiFormRequest extends FormRequest
{
    /**
     * Force JSON & unify validation error shape
     */
    protected function failedValidation(Validator $validator)
    {
        $payload = [
            'success' => false,
            'message' => 'Validation error',
            'errors'  => $validator->errors(), // field => [msgs...]
        ];

        throw new HttpResponseException(
            response()->json($payload, 422)
        );
    }

    /**
     * Unify authorization error shape if authorize() returns false
     */
    protected function failedAuthorization()
    {
        $payload = [
            'success' => false,
            'message' => 'This action is unauthorized.',
        ];

        throw new HttpResponseException(
            response()->json($payload, 403)
        );
    }
}
