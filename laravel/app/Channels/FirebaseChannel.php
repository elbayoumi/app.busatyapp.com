<?php

namespace App\Channels;

use App\Models\Notification as NotificationModel;
use App\Models\Topic;
use App\Services\Firebase\FcmService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseChannel
{
    protected $fcmService;

    public function __construct()
    {
        // Initialize the FcmService or any other service here if needed
        $this->fcmService = app(FcmService::class);
    }

    public function send($notifiable, Notification $notification)
    {

        // Get the Firebase notification data
        $data = $notification->toFirebase($notifiable);

        // Fetch Firebase tokens from the notifiable entity

        $from = $data['from'];
        // $fcmTokens = $data['fcmTokens']; // Store parents data if needed
        $title = $data['title'];
        $body = $data['body'];
        $topic = $data['topic'];
        $type = $data['type'];
        $additional = $data['additional'];

        $to = $data['to'];
        $notificationModel = NotificationModel::create([
            'data' => [
                'from' => $from ,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'additional' => $additional,

            ],
        ]);

        $topic = Topic::firstOrCreate(['name' => $topic]);
        // $topic->users($userModel);
        // $notificationModel->$to()->attach($fcmTokens['ids']);

        $notificationModel->topics()->attach($topic->id);

        return $this->fcmService->sendNotificationToTopic($topic->name, $data['title'], $data['body']);
    }
}
