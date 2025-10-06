<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\TemporaryAddressOldBus;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\MoveStudentToOldBus;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    MoveStudentToOldBus::class,
  ];
  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('queue:work --stop-when-empty')
      ->hourly();

    $records = TemporaryAddressOldBus::whereHas('temporaryAddress', function ($query) {
      $query->where('to_date', '<=', Carbon::now()->format('Y-m-d'));
    })->get()->filter(
        function ($record) {
          $currentDate = Carbon::now($record->timezone)->format('Y-m-d');
          return $record->temporaryAddress()
            ->where('to_date', '<', $currentDate)
            ->exists();
        }
      );

    foreach ($records as $record) {

      $timeZone = $record->timezone ?? config('app.timezone');

      $schedule->command('move:student ' . $record->id)
        ->dailyAt('00:00')
        ->timezone($timeZone);
    }
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
