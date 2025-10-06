<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;

class TransferStudentRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'   => ['required', 'uuid', 'exists:students,id'],
            'from_trip_id' => ['required', 'integer', 'exists:trips,id'],
            'to_trip_id'   => ['required', 'integer', 'different:from_trip_id', 'exists:trips,id'],
        ];
    }
}
