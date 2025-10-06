<?php

namespace App\Services\Schools;

use App\Models\School;
use App\Services\Schools\Actions\AttachTopicToSchoolAction;
use App\Services\Schools\Actions\CloneTripTypesAction;
use App\Services\Schools\Actions\CreateBusAction;
use App\Services\Schools\Actions\CreateFixedTripsAction;
use App\Services\Schools\Actions\CreateGradesAndClassroomsAction;
use Illuminate\Support\Facades\Log;

class SchoolSetupService
{
    /**
     * Initialize all required records for a new school.
     */
    public function setup(School $school): void
    {
        // Create the fixed trips (start and end of day)
        CreateFixedTripsAction::run($school);

        // Create a default bus
        $bus = CreateBusAction::run($school);

        // Create default grades, classrooms, and one student
        CreateGradesAndClassroomsAction::run($school, $bus);

        AttachTopicToSchoolAction::run($school);
        CloneTripTypesAction::run($school);

        Log::info("School setup completed for ID: {$school->id}");
    }
}
