<?php

namespace App\Http\Requests\Api\Schools;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class StoreBusesRequest extends FormRequest
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
            'name' => ['required', 'min:1', 'max:30', Rule::unique('buses')->where(function ($query) {
                return $query->where('school_id', $this->user()->id);
            })],
            'car_number' => ['required', 'min:1','max:15', Rule::unique('buses')->where(function ($query) {
                return $query->where('school_id', $this->user()->id);
            })],
            'notes' =>  ['nullable', 'max:255'],
        ];
    }
    protected function failedValidation(Validator  $validator)
    {
        $response = [
            'errors' => true,
            'messages' => 'error ',
            'errorMessages' => $validator->errors()->toArray(),
        ];

        // Throw HttpResponseException with the response
        throw new \Illuminate\Validation\ValidationException($validator, response()->json($response, 422));
    }
}
