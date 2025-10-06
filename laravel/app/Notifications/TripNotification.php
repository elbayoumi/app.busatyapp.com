<?php

namespace App\Notifications;

use App\Channels\DatabaseChannel;
use App\Channels\FirebaseChannel;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\Firebase\FcmService;

class TripNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $type;
    protected string $body;
    protected StudentRepositoryInterface $studentRepository;
    protected FcmService $fcmService;
    protected array $parents = []; // Initialize as an empty array
    protected string $topic;
    protected array $additional;

    public function __construct(string $title, string $body, string $type ='no-tracking',array $additional=[])
    {

        $this->title = $title;
        $this->type = $type;
        $this->body = $body;
        $this->additional = $additional;
        $this->studentRepository = app(StudentRepositoryInterface::class);
        $this->fcmService = new FcmService;
    }

    public function via($notifiable)
    {

        return [FirebaseChannel::class]; // Ensure this is correct
    }

    public function toFirebase($notifiable)
    {
        $this->topic = (string) 'tr' . $notifiable->id;

        // Fetch the parents data and store it in the $parents property
        $this->parents = $this->studentRepository->tripFcm($notifiable);
        // Fetch the Firebase tokens for the student

        // Subscribe the tokens to the 'trip' topic for notifications
        $this->fcmService->subscribeToTopic($this->topic, $this->parents['tokens']);

        return [
            'from' => '$this->type',
            'type' => $this->type,
            'title' => $this->title,
            'body' => $this->body,
            'topic' => $this->topic,
            'additional' => $this->additional,
            'fcmTokens' => $this->parents, // Store parents data if needed
            'to'=>'myParents'
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'parents' => $this->parents, // Store parents data if needed
            'title' => $this->title,
            'body' => $this->body,
            // 'topic' => $this->topic
        ];
    }

}
