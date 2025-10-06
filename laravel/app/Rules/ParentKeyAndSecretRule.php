<?php

namespace App\Rules;

use App\Models\Student;
use Illuminate\Contracts\Validation\Rule;

class ParentKeyAndSecretRule implements Rule
{
    protected $message;

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
        $parentKey = request()->input('parent_key');
        $student = Student::where('parent_key', $parentKey)
            ->where('parent_secret', $value)
            ->first();

        if ($student) {
            $user = request()->user();
            if ($user->hasStudent($student->id)) {
                $this->message = __('This child already exists');
                return false;
            }
            return true;
        }

        $this->message = __("the code or password is not valid");
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
