<?php

namespace App\Http\Requests\Attendant\Trips;

use App\Http\Requests\Api\BaseApiFormRequest;

class CheckInRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        // Add your policy/logic if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'lat'        => 'nullable|numeric',
            'long'       => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'The student_id field is required.',
            'student_id.exists'   => 'The selected student does not exist.',
            'lat.numeric'         => 'Latitude must be a number.',
            'long.numeric'        => 'Longitude must be a number.',
        ];
    }

    /**
     * Optional: tweak attributes names in errors
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'lat'        => 'latitude',
            'long'       => 'longitude',
        ];
    }
}
