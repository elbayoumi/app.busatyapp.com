<?php

namespace App\Http\Requests\parents;

use App\Rules\ParentKeyAndSecretRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ChildrensStoreRequest extends FormRequest
{
    protected $parentKeyAndSecretRule;

    public function __construct()
    {
        // Initialize custom rule for parent key and secret
        $this->parentKeyAndSecretRule = new ParentKeyAndSecretRule();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You might want to add authorization logic here if needed
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
            'parent_key' => ['required', 'size:6'],
            'parent_secret' => ['required', 'size:6',$this->parentKeyAndSecretRule],
            // Conditional validation for latitude and longitude
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->input('longitude')) {
                        $fail('Longitude is required when latitude is provided.');
                    }
                }
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->input('latitude')) {
                        $fail('Latitude is required when longitude is provided.');
                    }
                }
            ],
            'address' => [
                'nullable',
                'min:5',
                'max:255',
                // Additional check for address if either latitude or longitude is provided
                // function ($attribute, $value, $fail) {
                //     if (($this->input('latitude') || $this->input('longitude')) && !$value) {
                //         $fail('Address is required when latitude or longitude is provided.');
                //     }
                // }
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Custom response on validation failure
        $response = [
            'errors' => true,
            'messages' => 'Validation failed.',
            'errorMessages' => $validator->errors()->toArray(),
        ];

        // Throwing the validation exception with the custom response
        throw new ValidationException($validator, response()->json($response, 422));
    }
}
