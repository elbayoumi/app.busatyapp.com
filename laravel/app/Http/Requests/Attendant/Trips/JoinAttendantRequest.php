<?php

namespace App\Http\Requests\Attendant\Trips;

use App\Http\Requests\Api\BaseApiFormRequest;

class JoinAttendantRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        // Add your policy/ability check if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'attendant_id' => ['required', 'integer', 'exists:attendants,id'],
            'joined_at'    => ['nullable', 'date'], // default now() if missing
        ];
    }
}
