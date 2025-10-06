<?php

namespace App\Observers;

use App\Enum\TripStatusEnum;
use App\Models\My_Parent;
use TripFacadeService;
use App\Models\Trip;

class TripObserver
{



    public function created(Trip $trip): void
    {
        // $user = request()->user();
        // $trip->customIdentifier()->create();

        // if (request()->notify) {
        //     TripFacadeService::open($trip, My_Parent::class);
        // }

        // $trip->creator()->create([
        //     'userable_id' => $user->id,
        //     'userable_type' => $user->getMorphClass()
        // ]);

        // TripFacadeService::handleTripCreated($trip);
    }

    public function updated(Trip $trip): void
    {
        // if (request()->notify) {
        //     if ($trip->trip_type->value === 'start_day') {
        //         TripFacadeService::endTrip($trip, My_Parent::class);
        //     }
        // }

        // // ✅ لو في تغيير بالـ status أو end_at نستدعي الخدمة
        // if (request()->user()->typeAuth == 'attendants') {
        //     $changes = $trip->getChanges();
        //     TripFacadeService::handleTripUpdated($trip, $changes);
        // }
    }

    public function deleted(Trip $trip): void {}
    public function restored(Trip $trip): void {}
    public function forceDeleted(Trip $trip): void {}
}
