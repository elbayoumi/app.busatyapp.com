<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class AssignBusRequest extends BaseApiFormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'bus_id' => ['required', 'integer', 'exists:buses,id'],
        ];
    }
}
