<?php

namespace App\Observers;

use App\Models\TripStudent;
use App\Services\Schools\SchoolNotificationService;

class TripStudentObserver
{
  /**
   * Handle the TripStudent "created" event.
   *
   * @param  \App\Models\TripStudent  $tripStudent
   * @return void
   */
  public function created(TripStudent $tripStudent)
  {
  }

  /**
   * Handle the TripStudent "updated" event.
   *
   * @param  \App\Models\TripStudent  $tripStudent
   * @return void
   */
  public function updated(TripStudent $tripStudent)
  {
    $changes = $tripStudent->getChanges();
    if ( isset($changes['is_absent']) 
    && $changes['is_absent'] == true 
    && 'attendants' == request()->user()->typeAuth
    ) {
    // sending notification to school that the attendant make a student absent 
    $tripStudent->load('student.school' , 'student.bus' , 'trip');
    $school = $tripStudent->student->school;

    if ($school->student_absence_notification_status == 1) {

      $title = __('Student Absence');
      $body = __('The Student') 
        . " {$tripStudent->student->name} " 
        . __('is absent from the bus') 
        . " {$tripStudent->student->bus->name}"
        . __('at the trip')
        . $tripStudent->trip->trip_type == 'start_day' ? __('start day trip') : __('end day trip');

      $schoolNotificationService = new SchoolNotificationService(
        $school,
      );
      
      $schoolNotificationService->sendMulticastNotification(
        $title,
        $body
      );
    }
    }
  }

  /**
   * Handle the TripStudent "deleted" event.
   *
   * @param  \App\Models\TripStudent  $tripStudent
   * @return void
   */
  public function deleted(TripStudent $tripStudent)
  {
    //
  }

  /**
   * Handle the TripStudent "restored" event.
   *
   * @param  \App\Models\TripStudent  $tripStudent
   * @return void
   */
  public function restored(TripStudent $tripStudent)
  {
    //
  }

  /**
   * Handle the TripStudent "force deleted" event.
   *
   * @param  \App\Models\TripStudent  $tripStudent
   * @return void
   */
  public function forceDeleted(TripStudent $tripStudent)
  {
    //
  }
}
