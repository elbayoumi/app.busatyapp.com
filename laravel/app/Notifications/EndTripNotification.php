<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\Firebase\FcmService;

class EndTripNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $body;
    protected string $notifications_type;
    protected array $additional;
    protected string $from;

    protected StudentRepositoryInterface $studentRepository;
    protected FcmService $fcmService;
    protected array $parents = []; // Initialize as an empty array
    protected string $topic;

    public function __construct(string $title, string $body, string $notifications_type='no-tracking', array $additional=[],string $from='school')
    {
        $this->title = $title;
        $this->body = $body;
        $this->notifications_type = $notifications_type;
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
        // Fetch the Firebase tokens for the student
        // Subscribe the tokens to the 'string5Topic' topic for notifications
        $this->topic = (string) 'tr' . $notifiable->id;
        $this->parents = $this->studentRepository->tripFcm($notifiable);

        return [
            'title' => $this->title,
            'body' => $this->body,
            'topic' => $this->topic,
            'additional' => $this->additional,
            'parents' => $this->parents, // Store parents data if needed
            'to'=>'myParents',
            'type' => $this->notifications_type,
            'from' => $this->from,

        ];
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
