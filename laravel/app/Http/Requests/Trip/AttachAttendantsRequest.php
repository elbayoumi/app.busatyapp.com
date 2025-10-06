<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class AttachAttendantsRequest extends BaseApiFormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'mode' => ['nullable', 'in:sync,attach'], // default attach
            'attendants' => ['required', 'array', 'min:1'],
            'attendants.*' => ['integer', 'exists:attendants,id'],
        ];
    }
}
