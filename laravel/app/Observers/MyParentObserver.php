<?php

namespace App\Observers;

use App\Models\My_Parent;
use App\Models\Topic;

class MyParentObserver
{
    /**
     * Handle the My_Parent "created" event.
     *
     * @param  \App\Models\My_Parent  $my_Parent
     * @return void
     */
    public function created(My_Parent $my_Parent)
    {
        $topic=$my_Parent->getMorphClass();
        $topic = Topic::firstOrCreate(['name' => $topic]);

        // $my_Parent->topics()->attach($topic);
        $my_Parent->topics()->syncWithoutDetaching([$topic->id]);

    }

    /**
     * Handle the My_Parent "updated" event.
     *
     * @param  \App\Models\My_Parent  $my_Parent
     * @return void
     */
    public function updated(My_Parent $my_Parent)
    {
        //
    }

    /**
     * Handle the My_Parent "deleted" event.
     *
     * @param  \App\Models\My_Parent  $my_Parent
     * @return void
     */
    public function deleted(My_Parent $my_Parent)
    {
        //
    }

    /**
     * Handle the My_Parent "restored" event.
     *
     * @param  \App\Models\My_Parent  $my_Parent
     * @return void
     */
    public function restored(My_Parent $my_Parent)
    {
        //
    }

    /**
     * Handle the My_Parent "force deleted" event.
     *
     * @param  \App\Models\My_Parent  $my_Parent
     * @return void
     */
    public function forceDeleted(My_Parent $my_Parent)
    {
        //
    }
}
