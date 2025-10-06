<?php

namespace App\Observers;

use App\Models\School;
use App\Models\Topic;
use App\Models\TripType;
use App\Services\Schools\SchoolSetupService;
use Illuminate\Support\Facades\Log;

class SchoolObserver
{
    /**
     * Handle the School "created" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function created(School $school)
    {
        // Call school setup service
        app(SchoolSetupService::class)->setup($school);
    }

    /**
     * Handle the School "updated" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function updated(School $school)
    {
        //
    }

    /**
     * Handle the School "deleted" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function deleted(School $school)
    {
        //
    }

    /**
     * Handle the School "restored" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function restored(School $school)
    {
        //
    }

    /**
     * Handle the School "force deleted" event.
     *
     * @param  \App\Models\School  $school
     * @return void
     */
    public function forceDeleted(School $school)
    {
        //
    }
}
