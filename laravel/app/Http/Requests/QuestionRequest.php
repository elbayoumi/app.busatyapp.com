<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string|max:2048',
            'lang' => 'required|in:en,ar',
            'type' => 'nullable|string|max:255|in:parents,schools,attendants',
            // Add other validation rules as needed
        ];
    }
    public function messages()
    {
        return [
            'question.required' => 'The question field is required.',
            'question.string' => 'The question must be a string.',
            'question.max' => 'The question may not be greater than 255 characters.',
            'answer.string' => 'The answer must be a string.',
            'type.in' => 'The selected type is invalid.',
            // Add other custom messages as needed
        ];
    }
}
