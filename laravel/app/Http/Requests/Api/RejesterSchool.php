<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RejesterSchool extends FormRequest
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
            'name' => 'required|min:4',
            'email' => 'required|email|unique:schools',
            'password' => 'required|min:8',
            'phone' =>    'required|min:10|unique:schools',
            'address' =>  'required|min:4',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180'
        ];
    }
}
