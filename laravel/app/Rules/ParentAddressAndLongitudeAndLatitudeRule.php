<?php

namespace App\Rules;

use App\Models\Student;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class ParentAddressAndLongitudeAndLatitudeRule implements Rule
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

        $parentKey = Request::input('parent_key');
        $parentSecret = Request::input('parent_secret');

        // Check if a student exists with the given parent_key and parent_secret
        $studentExists = Student::where('parent_key', $parentKey)
            ->where('parent_secret', $parentSecret)
            ->whereNull('longitude')
            ->whereNull('latitude')
            ->exists();

        if ($studentExists) {
            return false;

        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Address, longitude, and latitude are required together if any of them is provided.';
    }
}
