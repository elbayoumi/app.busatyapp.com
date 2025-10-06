<?php

namespace App\Observers;

use App\Models\TemporaryAddress;
use App\Enum\TemporaryAddressStatusEnum;
use App\Services\ParentNotificationService;
use App\Services\AttendantNotificationService;
use App\Services\Schools\SchoolNotificationService;

class TemporaryAddressObserver
{
  /**
   * Handle the TemporaryAddress "created" event.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return void
   */
  public function created(TemporaryAddress $temporaryAddress)
  {
    // sending notification to school that the temporaryAddress has started
    $temporaryAddress->load('student.my_Parents', 'student.school');
    $school = $temporaryAddress->student->school;

    if ($school->student_address_notification_status == 1 && request()->user()->typeAuth == 'parents') {

      $title = __('A temporary address change request added for a student.');
      $body = __('The parent of the student ') . " ({$temporaryAddress->student->name}) " . __('has added a temporary address. Please take an action.');

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
   * Handle the TemporaryAddress "updated" event.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return void
   */
  public function updated(TemporaryAddress $temporaryAddress)
  {
    if (request()->user()->typeAuth != 'schools') {
      return;
    }

    // get the changes in the update 
    $changes = $temporaryAddress->getChanges();

    //  if accept_status is updated 
    if ((isset($changes['accept_status']))) {

      $temporaryAddress->load('student.bus.attendants', 'student.my_Parents');

      $title = 'A temporary address request';

      if ($changes['accept_status'] == TemporaryAddressStatusEnum::getArray()[1] || $changes['accept_status'] == TemporaryAddressStatusEnum::getArray()[2]) {
        
        if ($changes['accept_status'] == TemporaryAddressStatusEnum::getArray()[1] ) {
        
          $title .= ' has been accepted ';
          $body = __('The temporary address for the student :name has been accepted' , [
                    'name' => $temporaryAddress->student->name
                  ]);
        
          // sending notifications to attendants on rejected only
          $attendantNotificationService = new AttendantNotificationService(
            $temporaryAddress->student->bus->attendants
          );
        
          $attendantNotificationService->sendMulticastNotification(
            $title,
            $body
          );

        } else if ($changes['accept_status'] == TemporaryAddressStatusEnum::getArray()[2]) {
          $title .= ' has been rejected ';
          $body = __('The temporary address for the student :name has been rejected' , [
                    'name' => $temporaryAddress->student->name
                  ]);
          
        }

        // sending notifications to parents on rejected or approved 
        
        $parentNotificationService = new ParentNotificationService(
          $temporaryAddress->student->my_Parents
        );
        
        $parentNotificationService->sendMulticastNotification(
          __($title),
          $body
        );

      }
    }
  }

  /**
   * Handle the TemporaryAddress "deleted" event.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return void
   */
  public function deleted(TemporaryAddress $temporaryAddress)
  {
    //
  }

  /**
   * Handle the TemporaryAddress "restored" event.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return void
   */
  public function restored(TemporaryAddress $temporaryAddress)
  {
    //
  }

  /**
   * Handle the TemporaryAddress "force deleted" event.
   *
   * @param  \App\Models\TemporaryAddress  $temporaryAddress
   * @return void
   */
  public function forceDeleted(TemporaryAddress $temporaryAddress)
  {
    //
  }
}
