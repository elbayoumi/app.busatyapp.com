<?php

namespace App\Observers;

use App\Models\Address;
use App\Enum\AddressStatusEnum;
use App\Services\Schools\SchoolNotificationService;

class AddressObserver
{
    /**
     * Handle the Address "created" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function created(Address $address)
    {

        // sending notification to school that the trip has started
        $address->load('student', 'parent', 'school');

        $school = $address->school;

        if ($school->student_address_notification_status == 1) {

            if ($address->status == AddressStatusEnum::ACCEPT) {

                $title = __('The address changed for the student') . " {$address->student->name}";
                $body = __('The parent') . " {$address->parent->name} "
                    . __('has changed the address of the student')
                    . " {$address->student->name}"
                    . __('to') . " {$address->address}";
            } elseif ($address->status == AddressStatusEnum::PROCESSING) {

                $title = __('An address changing request for the student') . " {$address->student->name}";
                $body = __('The parent') . " {$address->parent->name} "
                    . __('has applied for address changing request for the student')
                    . " {$address->student->name}"
                    . __('to') . " {$address->address}";
            }

            $schoolNotificationService = new SchoolNotificationService(
                $school,
            );

            $schoolNotificationService->sendMulticastNotification(
                $title,
                $body
            );
        }
    }

    /**
     * Handle the Address "updated" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function updated(Address $address)
    {
        //
    }

    /**
     * Handle the Address "deleted" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function deleted(Address $address)
    {
        //
    }

    /**
     * Handle the Address "restored" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function restored(Address $address)
    {
        //
    }

    /**
     * Handle the Address "force deleted" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function forceDeleted(Address $address)
    {
        //
    }
}
