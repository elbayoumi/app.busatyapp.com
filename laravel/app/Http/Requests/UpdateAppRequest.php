<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users (adjust as needed for permissions)
        return true;
    }

    /**
     * Define the validation rules for the update request.
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|integer|in:0,1',
            'google_auth' => 'nullable|integer|in:0,1', // Google Auth toggle
            'version' => 'nullable|string|max:10',
            'is_updating' => 'nullable|boolean',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'status.integer' => 'The status must be an integer.',
            'status.in' => 'Invalid status value.',

            'google_auth.integer' => 'Google Auth must be an integer.',
            'google_auth.in' => 'Invalid Google Auth value.',

            'version.string' => 'The version must be a string.',
            'version.max' => 'The version must not exceed 10 characters.',

            'is_updating.boolean' => 'The updating mode must be true or false.',
        ];
    }
}
