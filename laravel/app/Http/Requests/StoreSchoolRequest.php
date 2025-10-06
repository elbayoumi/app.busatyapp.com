<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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
            'name'      => ['nullable', 'string', 'max:90'],
            'email'     => ['nullable', 'string', 'email', 'max:90','unique:schools'],
            'phone'     => ['nullable','unique:schools'],
            'password'  => ['nullable', 'string', 'min:6'],
            'address'   => ['nullable', 'string', 'max:150'],
            'city_name' => ['nullable', 'max:150'],
            'status'    => ['nullable'],
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required'),
            'email.required' => trans('validation.unique'),
            'phone.required' => trans('validation.unique'),
            'password.required' => trans('validation.required'),
            'address.required' => trans('validation.required'),
            'status.required' => trans('validation.required'),
        ];
    }
}
