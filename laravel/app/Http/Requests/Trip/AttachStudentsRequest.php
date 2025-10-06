<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class AttachStudentsRequest extends BaseApiFormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Accept either full replace (sync) or additive (attach)
            'mode' => ['nullable', 'in:sync,attach'], // default attach
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['uuid', 'exists:students,id'],
        ];
    }
}
