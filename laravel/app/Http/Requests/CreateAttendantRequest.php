<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttendantRequest extends FormRequest
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
            'username' => 'required|string|unique:attendants|max:255', // Adjust max length as needed
            'password' => 'required|string|min:8|confirmed', // Consider password complexity rules
            'name' => 'required|string|max:255',
            'gender_id' => 'nullable|integer|exists:genders,id', // Adjust if gender_id is a string
            'religion_id' => 'nullable|integer|exists:religions,id', // Adjust if religion_id is a string
            'type__blood_id' => 'nullable|integer|exists:type__bloods,id', // Adjust if type__blood_id is a string
            'bus_id' => 'nullable|integer|exists:buses,id', // Adjust if bus_id is a string
            'Joining_Date' => 'nullable|date',
            'address' => 'nullable|string',
            'city_name' => 'nullable|string',
            'phone' => 'required|string', // Consider phone format validation
            'birth_date' => 'nullable|date',
            // No validation needed for email_verified_at, rememberToken, timestamps, typeAuth (default values)
        ];
    }
}
