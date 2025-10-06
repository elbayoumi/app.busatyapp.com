<?php

namespace App\Services\Schools\Actions;

use App\Models\Bus;
use App\Models\School;

class CreateBusAction
{
    /**
     * Create a default bus for the school.
     */
    public static function run(School $school): Bus
    {
        return Bus::create([
            'name' => 'bus 1',
            'notes' => 'details',
            'car_number' => rand(1020, 178500),
            'school_id' => $school->id,
        ]);
    }
}
