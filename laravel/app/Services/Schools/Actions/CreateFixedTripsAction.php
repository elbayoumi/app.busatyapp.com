<?php

namespace App\Services\Schools\Actions;

use App\Models\FixedTrip;
use App\Models\School;

class CreateFixedTripsAction
{
    /**
     * Create default fixed trips for the school.
     */
    public static function run(School $school): void
    {
        FixedTrip::create([
            'type' => 'end_day',
            'time_start' => '13:00:00',
            'time_end' => '15:00:00',
            'school_id' => $school->id,
        ]);

        FixedTrip::create([
            'type' => 'start_day',
            'school_id' => $school->id,
        ]);
    }
}
