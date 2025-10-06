<?php

namespace App\Console\Commands;

use App\Models\Attendant;
use Illuminate\Console\Command;
use App\Models\TemporaryAddressOldBus;
use App\Services\AttendantNotificationService;
use App\Services\Schools\SchoolNotificationService;

class MoveStudentToOldBus extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'move:student {record_id}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Moving the student from the temporary bus to the old bus';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $temporaryAddressOldBus = TemporaryAddressOldBus::find($this->argument('record_id'))
        ->load('temporaryAddress.student.school', 'bus');

    if ($temporaryAddressOldBus) {
      $originBusId = $temporaryAddressOldBus->bus_id;
      $temporaryBusId = $temporaryAddressOldBus->temporaryAddress->student->bus_id;
      $temporaryAddressOldBus->temporaryAddress->student->update(['bus_id' => $originBusId]);

      // sending a notification to attendants of both buses
      $attendants = Attendant::where('bus_id', $originBusId)
        ->orWhere('bus_id', $temporaryBusId)
        ->get();

      $title = __("A student returned to the origin bus");
      $body = __("The student ")
        . " ({$temporaryAddressOldBus->temporaryAddress->student->name}) "
        . __("has been moved automatically from the temporary bus to the bus ")
        . " ({$temporaryAddressOldBus->bus->name}) ";

      $attendantNotificationService = new AttendantNotificationService(
        $attendants
      );

      $attendantNotificationService->sendMulticastNotification(
        $title,
        $body
      );

      $schoolNotificationService = new SchoolNotificationService(
        $temporaryAddressOldBus->temporaryAddress->student->school
      );

      $schoolNotificationService->sendMulticastNotification(
        $title,
        $body
      );

      $temporaryAddressOldBus->delete();
    }
  }
}
