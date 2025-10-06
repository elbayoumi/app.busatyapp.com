<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreParentRequest extends FormRequest
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
            'name'             => 'required|max:255',
            'email'            => 'required|email|unique:my__parents,email',
            'password'         => 'required|string|min:6',
            'phone'            => 'required|max:255',
            'address'          => 'required|max:255',
            'status'           => 'required|in:1,0', // Enum validation for true/false
            'email_verified_at'=> 'required|in:1,0', // Enum validation for true/false
            'logo'             => 'nullable',

        ];
    }
}
