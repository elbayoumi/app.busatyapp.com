<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmtpRequest extends FormRequest
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
            // Validate SMTP host
            'smtp_host' => [
                'required', // The field is mandatory
                'string',   // Must be a string
                'max:255',  // Maximum length is 255 characters
                'regex:/^([a-zA-Z0-9]+\.)+[a-zA-Z]{2,}$/', // Must be a valid host format (e.g., smtp.example.com)
            ],

            // Validate SMTP port
            'smtp_port' => [
                'required',  // The field is mandatory
                'integer',   // Must be a valid integer
                'min:1',     // Port number must be at least 1
                'max:65535', // Port number must not exceed 65535
            ],

            // Validate SMTP encryption type
            'smtp_encryption' => [
                'required', // The field is mandatory
                'string',   // Must be a string
                'in:tls,ssl,starttls,none', // Must be one of the predefined values
            ],

            // Validate SMTP username
            'smtp_username' => [
                'required', // The field is mandatory
                'string',   // Must be a string
                'max:255',  // Maximum length is 255 characters
            ],

            // Validate SMTP password
            'smtp_password' => [
                'required', // The field is mandatory
                'string',   // Must be a string
                'min:8',    // Minimum length is 8 characters
                'max:255',  // Maximum length is 255 characters
            ],

            // Validate SMTP from address
            'smtp_from_address' => [
                'required', // The field is mandatory
                'email',    // Must be a valid email format
                'max:255',  // Maximum length is 255 characters
            ],

            // Validate SMTP from name
            'smtp_from_name' => [
                'required', // The field is mandatory
                'string',   // Must be a string
                'max:255',  // Maximum length is 255 characters
            ],
        ];
    }
    public function messages()
    {
        return [
            'smtp_host.required' => 'SMTP Host is required.',
            'smtp_host.regex' => 'The SMTP Host format is invalid. Example: smtp.example.com',
            'smtp_port.required' => 'SMTP Port is required.',
            'smtp_port.integer' => 'SMTP Port must be a number.',
            'smtp_port.min' => 'SMTP Port must be at least 1.',
            'smtp_port.max' => 'SMTP Port cannot exceed 65535.',
            'smtp_encryption.required' => 'SMTP Encryption is required.',
            'smtp_encryption.in' => 'SMTP Encryption must be one of: tls, ssl, starttls, none.',
            'smtp_from_address.email' => 'The SMTP From Address must be a valid email.',
            // Add other messages as needed...
        ];
    }
}
