<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentRequest extends FormRequest
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
            'email' => ['required', Rule::unique('my__parents')->ignore($this->id),],
            'phone'            => 'required|max:255',
            'password'            => 'nullable|max:255',
            'address'          => 'required|max:255',
            'status'           =>  'required|in:1,0',
            'email_verified_at'           =>  'required|in:1,0',

        ];
    }
}
