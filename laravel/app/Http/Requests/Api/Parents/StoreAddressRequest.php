<?php

namespace App\Http\Requests\Api\Parents;

use App\Models\Address;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreAddressRequest extends FormRequest
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
            'address'            => 'nullable|max:255',
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::exists('my__parent_student', 'student_id')->where(function ($query) {
                    return $query->where('my__parent_id', $this->user()->id);
                }),
                function ($attribute, $value, $fail) {
                    // Custom validation logic to check if there is already an opening request for the student
                    if (Address::where('student_id', $value)->watting()->exists()) {
                        return $fail(__('The student already has an open address request.'));
                    }
                }
            ],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }
    protected function failedValidation(Validator  $validator)
    {
        $response = [
            'errors' => true,
            'messages' => 'error',
            'errorMessages' => $validator->errors()->toArray(),
        ];

        // Throw HttpResponseException with the response
        throw new \Illuminate\Validation\ValidationException($validator, response()->json($response, 422));
    }

    public function messages()
    {
        return [
            'student_id.required' => __('The student ID is required.'),
            'student_id.exists' => __('This student does not exist or is not associated with this parent.'),
            'latitude.numeric' => __('Latitude must be a number.'),
            'longitude.numeric' => __('Longitude must be a number.'),
        ];
    }
}
