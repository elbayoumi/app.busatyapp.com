<?php

namespace App\Services;

use App\Models\My_Parent;
use App\Models\School;
use App\Models\Notification;
use App\Services\Firebase\NewFcmService;

class ParentNotificationService
{
  public $newFcmService;
  public $parents;

  public function __construct($parents)
  {
    $this->newFcmService = new NewFcmService;
    $this->parents = $parents;
  }

  public function sendMulticastNotification(
    string $title,
    string $body,
    array $data = []
  ) {

    try {
      // get tokens of the parent devices

      $eachParentTokens = $this->parents
        ->map(function ($row) {
          return $row->fcmTokens->pluck('fcm_token')->toArray();
        });

      $fcmTokens = collect($eachParentTokens)->flatten()->unique()->toArray();


      if (count($fcmTokens) > 0) {
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

        $notificationModel->users(My_Parent::class)
            ->attach($this->parents->pluck('id')->toArray());
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