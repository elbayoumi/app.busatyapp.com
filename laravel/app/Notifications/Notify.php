<?php

namespace App\Notifications;

use App\Channels\NotifyChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notify extends Notification
{
    use Queueable;

    protected string $title;
    protected string $type;
    protected string $body;
    protected string $topic;
    protected array $additional;
    protected string $userModel;

    public function __construct(string $title, string $body , $topic,$userModel, string $type ='no-tracking',array $additional=[])
    {

        $this->title = $title;
        $this->type = $type;
        $this->body = $body;
        $this->userModel = $userModel;
        $this->topic = $topic;
        $this->additional = $additional;

    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [NotifyChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFirebase($notifiable)
    {
        return [
            'title' => $this->title,
            'from' => request()?->user()?->name,
            'body' => $this->body,
            'type' => $this->type,
            'topic' => $this->topic,
            'userModel' => $this->userModel,
            'additional' => $this->additional,

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
