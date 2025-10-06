<?php

namespace App\Http\Requests\Attendant\Trips;

use App\Http\Requests\Api\BaseApiFormRequest;

class MarkStatusRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'The student_id field is required.',
            'student_id.exists'   => 'The selected student does not exist.',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id' => 'student',
        ];
    }
}
