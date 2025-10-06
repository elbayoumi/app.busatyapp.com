<?php

namespace Database\Factories;

use App\Models\Attendant;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class busFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $schools = [1,2,3,4,5,6,7,8,9,10,];
        return [

            'name' => $this->faker->name(),
            'notes' => $this->faker->text(),
            'car_number'  =>  Str::random(8),
            'school_id' => School::factory(),
            'attendant_driver_id' => Attendant::factory(),
            'attendant_admins_id' => Attendant::factory(),

        ];
    }
}

