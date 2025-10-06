<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
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

        $rules = [
            'name' => ['required', 'string', 'max:90'],
            'email' => 'required|email|unique:schools',
            'phone' => 'required',
            'address'   => ['required', 'string', 'max:150'],
            'status'    => ['required'],
            'email_verified_at'    => ['required'],
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $school = $this->route()->parameter('school');

            $rules['email'] = 'required|email|unique:schools,id,' . $school->id;
        }//end of if

        return $rules;


    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'type' => 'school'
        ]);

    }//end of prepare for validation
    public function messages()
    {
        return [
            'name.required' => trans('validation.required'),
            'address.required' => trans('validation.required'),
            'city_name.required' => trans('validation.required'),
            'status.required' => trans('validation.required'),
        ];
    }
}
