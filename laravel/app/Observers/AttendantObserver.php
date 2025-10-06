<?php

namespace App\Observers;

use App\Models\Attendant;
use App\Models\Topic;

class AttendantObserver
{
    /**
     * Handle the Attendant "created" event.
     *
     * @param  \App\Models\Attendant  $attendant
     * @return void
     */
    public function created(Attendant $attendant)
    {
        $topic = $attendant->getMorphClass();
        $topic = Topic::firstOrCreate(['name' => $topic]);

        $attendant->topics()->attach($topic);
    }

    /**
     * Handle the Attendant "updated" event.
     *
     * @param  \App\Models\Attendant  $attendant
     * @return void
     */
    public function updated(Attendant $attendant)
    {
        if (request()->has('password')) {

            $attendant->tokens()->delete();
        }
        // dd($attendant);
    }

    /**
     * Handle the Attendant "deleted" event.
     *
     * @param  \App\Models\Attendant  $attendant
     * @return void
     */
    public function deleted(Attendant $attendant)
    {
        //
    }

    /**
     * Handle the Attendant "restored" event.
     *
     * @param  \App\Models\Attendant  $attendant
     * @return void
     */
    public function restored(Attendant $attendant)
    {
        //
    }

    /**
     * Handle the Attendant "force deleted" event.
     *
     * @param  \App\Models\Attendant  $attendant
     * @return void
     */
    public function forceDeleted(Attendant $attendant)
    {
        //
    }
}
