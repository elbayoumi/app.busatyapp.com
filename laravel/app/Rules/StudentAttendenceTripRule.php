<?php

namespace App\Rules;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\Rule;

class StudentAttendenceTripRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Attendance::where('student_id',$value)->where('school_id',request()->user()->bus_id)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'هذا الطالب غير موجود في نفس مدرستك.';
    }
}
