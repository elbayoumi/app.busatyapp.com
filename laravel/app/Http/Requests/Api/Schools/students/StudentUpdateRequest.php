<?php

namespace App\Http\Requests\Schools\students;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Can be replaced with policies
    }

    public function rules(): array
    {
        $schoolId = $this->user()->id;

        return [
            // Core fields
            'name'         => ['required', 'string', 'min:3', 'max:100'],
            'phone'        => ['nullable', 'string', 'min:10', 'max:20', 'regex:/^[0-9]+$/'],
            'gender_id'    => ['nullable', 'integer', 'exists:genders,id'],
            'address'      => ['nullable', 'string', 'min:5', 'max:100'],

            // Geo
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],

            // Grade & classroom must belong to same school (and same grade)
            'grade_id'     => [
                'required', 'integer', 'exists:grades,id',
                Rule::exists('school_grade', 'grade_id')->where(fn($q) =>
                    $q->where('school_id', $schoolId)
                ),
            ],
            'classroom_id' => [
                'required', 'integer', 'exists:classrooms,id',
                Rule::exists('classrooms', 'id')->where(fn($q) =>
                    $q->where('school_id', $schoolId)
                      ->where('grade_id', $this->input('grade_id'))
                ),
            ],

            // Optional trip (many-to-many attachment)
            'trip_id' => [
                'nullable', 'integer',
                Rule::exists('trips', 'id')->where(fn($q) =>
                    $q->where('school_id', $schoolId)
                ),
            ],

            // Logo file (if sent as file)
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // If sending base64 instead: switch to ['nullable','string'] and handle decoding in controller/service
        ];
    }

    public function messages(): array
    {
        return [
            // Name
            'name.required' => __('The student name is required.'),
            'name.string'   => __('The name must be a valid string.'),
            'name.min'      => __('The name must be at least 3 characters.'),
            'name.max'      => __('The name may not exceed 100 characters.'),

            // Phone
            'phone.min'    => __('The phone number must be at least 10 digits.'),
            'phone.max'    => __('The phone number may not exceed 20 digits.'),
            'phone.regex'  => __('The phone number must contain only digits.'),

            // Gender
            'gender_id.exists' => __('The selected gender is invalid.'),

            // Address
            'address.min' => __('The address must be at least 5 characters.'),
            'address.max' => __('The address may not exceed 100 characters.'),

            // Latitude / Longitude
            'latitude.numeric'  => __('Latitude must be a numeric value.'),
            'latitude.between'  => __('Latitude must be between -90 and 90.'),
            'longitude.numeric' => __('Longitude must be a numeric value.'),
            'longitude.between' => __('Longitude must be between -180 and 180.'),

            // Grade
            'grade_id.required' => __('The grade field is required.'),
            'grade_id.exists'   => __('The selected grade is invalid or not assigned to this school.'),

            // Classroom
            'classroom_id.required' => __('The classroom field is required.'),
            'classroom_id.exists'   => __('The selected classroom is invalid or not linked to the specified grade.'),

            // Trip
            'trip_id.exists' => __('The selected trip is invalid or not assigned to this school.'),

            // Logo
            'logo.file'  => __('The logo must be a valid file.'),
            'logo.mimes' => __('The logo must be a JPG, JPEG, PNG, or WEBP image.'),
            'logo.max'   => __('The logo may not be larger than 2 MB.'),
        ];
    }
}
