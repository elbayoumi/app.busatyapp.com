<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Validation\Rule;

class StoreTripRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Trip name (must be unique within the same school)
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('trips')->where(fn ($query) =>
                    $query->where('school_id', auth()->user()->id)
                ),
            ],

            // Optional: attach a bus on creation
            'bus_id' => ['nullable', 'integer', 'exists:buses,id'],

            // Trip type (start or end)
            'trip_type' => ['required', 'string', 'in:start_day,end_day'],

            // Optional: attach attendants (supervisors)
            'attendants'   => ['array'],
            'attendants.*' => ['integer', 'exists:attendants,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('The trip name is required.'),
            'name.unique'   => __('A trip with this name already exists for this school.'),
            'trip_type.required' => __('Trip type is required.'),
            'trip_type.in'  => __('Trip type must be either start_day or end_day.'),
            'bus_id.exists' => __('The selected bus does not exist.'),
            'attendants.array' => __('Attendants must be provided as an array.'),
            'attendants.*.exists' => __('One or more attendants do not exist.'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => __('Trip Name'),
            'trip_type'  => __('Trip Type'),
            'bus_id'     => __('Bus'),
            'attendants' => __('Attendants'),
        ];
    }
}
