<?php

namespace App\Services;

use App\Models\Attendant;
use App\Models\Notification;
use App\Services\Firebase\NewFcmService;

class AttendantNotificationService
{
  public $newFcmService;
  public $attendants;

  public function __construct($attendants)
  {
    $this->newFcmService = new NewFcmService;
    $this->attendants = $attendants;
  }

  public function sendMulticastNotification(
    string $title,
    string $body,
    array $data = []
  ) {

    try {

      // get tokens of the attendant devices
      $eachAttendantTokens = $this->attendants
        ->map(function ($row) {
          return $row->fcmTokens->pluck('fcm_token')->toArray();
        });

      $fcmTokens = collect($eachAttendantTokens)->flatten()->unique()->toArray();

      if (count($fcmTokens) > 0) {
        // store notification in the database
        $notificationModel = Notification::create([
          'data' => [
            'from' => 'attendant',
            'title' => $title,
            'body' => $body,
            'type' => 'no-tracking',
            'additional' => $data ??[],
          ],
        ]);

        $notificationModel->users(Attendant::class)
            ->attach($this->attendants->pluck('id')->toArray());

        // sending notification using FCM

        return $this->newFcmService->sendMulticast(
          $fcmTokens,
          $title,
          $body,
          $data
        );
      }

    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage(), 'status' => false], 500);
    }
  }
}