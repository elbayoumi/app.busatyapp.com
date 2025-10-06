<?php

namespace App\Services\Schools\Actions;

use App\Models\School;
use App\Models\TripType;

class CloneTripTypesAction
{
    public static function run(School $school): void
    {
        $originalTripTypes = TripType::select('name', 'description')
            ->where('tripable_type', 'App\Models\Staff')
            ->get()
            ->toArray();

        $school->createdTripTypes()->createMany($originalTripTypes);
    }
}
