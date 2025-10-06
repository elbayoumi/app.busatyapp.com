<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadStudentLogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'logo'       => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'student_id' => 'required|exists:students,id',
        ];
    }

    /**
     * Custom error messages (translatable)
     */
    public function messages(): array
    {
        return [
            'logo.required' => __('Logo is required'),
            'logo.image'    => __('Logo must be an image'),
            'logo.mimes'    => __('Allowed formats: jpeg, png, jpg, webp, gif'),
            'logo.max'      => __('Maximum size allowed is 4MB'),

            'student_id.required' => __('Student ID is required'),
            'student_id.exists'   => __('Student not found'),
        ];
    }

    /**
     * Force JSON error format
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            JSONerror($validator->errors(), 500)
        );
    }
}
