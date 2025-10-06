<?php

namespace App\Http\Requests;

use App\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreStudentsToBusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $schoolId = $this->user()->id;

        return [
            'bus_id' => [
                'nullable',
                Rule::exists('buses', 'id')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                })
            ],
            'student_id' => [
                'required',
                'array',
                'min:1',
                Rule::exists('students', 'id')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                })
            ],
            'trip_type' => [
                'required_if:bus_id,!=,null',
                'in:full_day,end_day,start_day'
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors(); // Convert all errors to a plain array
        throw new HttpResponseException($errors,403);
    }

}
