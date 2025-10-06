<?php

namespace App\Notifications;

use App\Channels\DatabaseChannel;
use App\Channels\FirebaseChannel;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\Firebase\FcmService;

class StudentNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $body;
    protected StudentRepositoryInterface $studentRepository;
    protected FcmService $fcmService;
    protected array $parents = []; // Initialize as an empty array
    protected string $topic;

    protected string $notifications_type;
    protected string $from;
    protected array $additional;

    public function __construct(string $title, string $body, string $notifications_type ='no-tracking',array $additional =[] ,string $from='school')
    {
        $this->from = $from;
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
        // $this->topic = (string) 'student' . $notifiable->id;
        $this->topic= $notifiable->customIdentifier->identifier;

        // Fetch the parents data and store it in the $parents property
        // $this->parents = $this->studentRepository->studentFcm($notifiable);
        // Fetch the Firebase tokens for the student

        // Subscribe the tokens to the 'trip' topic for notifications
        // $this->fcmService->subscribeToTopic($this->topic, $this->parents['tokens']);
        // $classes = [
        //     get_class($notifiable) => $notifiable,
        //     get_class($notifiable->schools()->getModel()) => $notifiable->schools,
        // ];
        // $originalText = "اسم الطالب هو {{Student}}->name";
        // $body=textToClass($originalText, $classes);
        return [
            'title' => $this->title,
            'fcmTokens' => $this->parents,
            'type' => $this->notifications_type,
            'from' => $this->from,
            'body' => $this->body,
            'topic' => $this->topic,
            'additional' => $this->additional,
            'parents' => $this->parents, // Store parents data if needed
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
