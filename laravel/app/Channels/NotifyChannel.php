<?php

namespace App\Channels;

use App\Models\Notification as NotificationModel;
use App\Models\Topic;
use App\Services\Firebase\FcmService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyChannel
{
    protected $fcmService;

    public function __construct()
    {
        // Initialize the FcmService or any other service here if needed
        $this->fcmService = app(FcmService::class);
    }

    public function send($notifiable, Notification $notification)
    {
        $startTime = microtime(true);

        // Get the Firebase notification data
        $data = $notification->toFirebase($notifiable);

        // Fetch Firebase tokens from the notifiable entity

        $from = $data['from'];
        $userModel = $data['userModel']; // Store parents data if needed
        $title = $data['title'];
        $body = $data['body'];
        $topic = $data['topic'];
        $type = $data['type'];
        $additional = $data['additional'];

        $this->fcmService->sendNotificationToTopic($topic, $title,$body);
        $notificationModel = NotificationModel::create([
            'data' => [
                'from' => $from ,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'additional' => $additional,

            ],
        ]);

        $topic = Topic::where('name' , $topic)->first();
        $userModelInstance = app($userModel);

        $modelIds = $topic->users($userModelInstance)->pluck("{$userModelInstance->getTable()}.id");
        $notificationModel->users($userModel)->attach($modelIds);

        $notificationModel->topics()->attach($topic->id);
        $endTime = microtime(true);

        // Calculate the duration in seconds
        $executionTime = $endTime - $startTime;

        // Log the execution time
        Log::info('Notification sent in ' . $executionTime . ' seconds.');
        Log::info('Send body to FCM : ' . $body);

        return true;
    }
}
