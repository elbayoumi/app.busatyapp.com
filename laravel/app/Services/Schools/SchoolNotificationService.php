<?php

namespace App\Services\Schools;

use App\Models\School;
use App\Models\Notification;
use App\Services\Firebase\NewFcmService;

class SchoolNotificationService
{
  public  $newFcmService;
  public  $school;

  public function __construct($school) 
  {
    $this->newFcmService = new NewFcmService;
    $this->school = $school;
  }

  public function sendMulticastNotification(
    string $title,
    string $body,
    array $data = []
  ) {

    try {

      // get tokens of the school devices
      $fcmTokens = $this->school->fcmTokens()->pluck('fcm_token')->toArray();

      if (count($fcmTokens) > 0 ) {
        // store notification in the database
        $notificationModel = Notification::create([
          'data' => [
            'from' => 'attendant',
            'title' => $title,
            'body' => $body,
            'type' => 'no-tracking',
            'additional' => $data ?? [],
          ],
        ]);

        $notificationModel->users(School::class)->attach($this->school->id);

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