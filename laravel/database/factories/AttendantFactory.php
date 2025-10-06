<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendantFactory extends Factory
{


    public function definition()
    {


        $schools = [1,2,3,4,5,6,7,8,9,10,];
        $arrayValues = ['drivers', 'admins'];
        return [
            'school_id' => School::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address'  =>  $this->faker->address(),
            'city_name' => $this->faker->city(),
            'birth_date'  =>  $this->faker->date(),
            'Joining_Date'  =>  $this->faker->date(),
            'gender_id' =>1,
            'religion_id' =>1,
            'type__blood_id' =>1,
            'type' => $arrayValues[rand(0,1)],
        ];
    }


}
