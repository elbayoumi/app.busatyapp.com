<?php

namespace App\Http\Requests\Api\Schools\students;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class StudentRequest extends FormRequest
{
    /**
     * Authorization for this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating/updating a student.
     * Note: bus_id was replaced with trip_id (scoped to the same school).
     */
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'min:3', 'max:100'],

            'phone'      => ['nullable', 'regex:/^[0-9]+$/', 'min:10', 'max:20'],

            'grade_id'   => [
                'required',
                'exists:grades,id',
                Rule::exists('school_grade')->where(function ($query) {
                    return $query
                        ->where('school_id', request()->user()->id)
                        ->where('grade_id', request()->grade_id);
                }),
            ],

            'gender_id'  => ['nullable', 'exists:genders,id'],

            'address'    => ['nullable', 'min:5', 'max:100'],

            'latitude'   => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'  => ['nullable', 'numeric', 'between:-180,180'],


            // ✅ Replaced bus_id with trip_id (must belong to the same school)
            'trip_id'    => [
                'nullable',
                Rule::exists('trips', 'id')->where(function ($query) {
                    return $query->where('school_id', request()->user()->id);
                }),
            ],

            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                Rule::exists('classrooms', 'id')->where(function ($query) {
                    return $query
                        ->where('school_id', request()->user()->id)
                        ->where('grade_id', request()->grade_id);
                }),
            ],

            'logo'       => ['nullable'],
        ];
    }

    /**
     * Custom failed validation response (kept as-is).
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'errors'   => true,
            'messages' => $validator->errors()->all(),
        ];

        throw new \Illuminate\Validation\ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
