<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateTripRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare for validation: merge "data" wrapper if sent as { "data": { ... } }
     */
    protected function prepareForValidation(): void
    {
        $wrapped = $this->input('data');
        if (is_array($wrapped)) {
            $this->merge($wrapped);
        }
    }

    public function rules(): array
    {
        $trip = $this->route('trip');

        return [
            // Trip name (must be unique within the same school)
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('trips', 'name')
                    ->ignore($trip?->id)
                    ->where(fn($query) =>
                        $query->where('school_id', auth()->user()->id)
                    ),
            ],

            // Trip type (start or end)
            'trip_type' => ['sometimes', 'string', 'in:start_day,end_day'],

            // Optional: attach a bus or unassign (nullable)
            'bus_id' => ['nullable', 'integer', 'exists:buses,id'],

            // Optional: start and end times (if your DB uses H:i:s)
            'start_time' => ['sometimes', 'date_format:H:i:s'],
            'end_time'   => ['sometimes', 'date_format:H:i:s'],

            // Optional attendants (supervisors)
            'attendants'   => ['sometimes', 'array'],
            'attendants.*' => ['integer', 'exists:attendants,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string'  => __('The trip name must be a string.'),
            'name.max'     => __('The trip name must not exceed 255 characters.'),
            'name.unique'  => __('A trip with this name already exists for this school.'),
            'trip_type.in' => __('Trip type must be either start_day or end_day.'),
            'bus_id.exists' => __('The selected bus does not exist.'),
            'start_time.date_format' => __('Start time must be in H:i:s format.'),
            'end_time.date_format'   => __('End time must be in H:i:s format.'),
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
            'start_time' => __('Start Time'),
            'end_time'   => __('End Time'),
        ];
    }
}
