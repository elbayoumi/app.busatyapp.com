<?php

namespace Database\Factories;

use App\Models\Attendant;
use App\Models\bus;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => School::factory(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address'  =>  $this->faker->address(),
            'city_name' => $this->faker->city(),
            'Date_Birth'  =>  $this->faker->date(),
            'gender_id' =>1,
            'religion_id' =>1,
            'type__blood_id' =>1,
            'parent_key' => Str::random(8),
            'parent_secret' => Str::random(8),
            'grade_id' => Grade::factory(),
            'classroom_id' => Classroom::factory(),
            'bus_id' => bus::factory(),
            'attendant_driver_id' => Attendant::factory(),
            'attendant_admins_id' => Attendant::factory(),
        ];



    }
}
