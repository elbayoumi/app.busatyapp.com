<?php

namespace App\Http\Requests\Attendant\Trips;

use App\Http\Requests\Api\BaseApiFormRequest;

class LeaveAttendantRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendant_id' => ['required', 'integer', 'exists:attendants,id'],
            'left_at'      => ['nullable', 'date'], // default now() if missing
        ];
    }
}
