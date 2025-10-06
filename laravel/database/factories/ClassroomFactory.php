<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'name' => $this->faker->name(),
            'school_id' => School::factory(),
            'grade_id' => Grade::factory(),

        ];
    }
}
