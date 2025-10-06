<?php
namespace App\Notifications;

use App\Mail\SenVerificationCode;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeNotification extends Notification
{
    public $user;
    public $verificationCode;
    public $modelName; // Model name passed as a string

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $modelName
     * @return void
     */
    public function __construct($user, $modelName)
    {
        $this->user = $user;
        $this->modelName = $modelName; // Store model name as a string
        $this->verificationCode = mt_rand(100000, 999999); // Generate a random verification code
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // The notification will be sent via email
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        // Delete any existing verification codes for this user
        $this->modelName::where('user_id', $this->user->id)->delete(); // Access the model dynamically

        // Create a new verification record using the model name
        $verification = $this->modelName::create([
            'user_id' => $this->user->id,
            'code' => $this->verificationCode,
        ]);

        // Generate the activation link with a temporary signed route and add the model_name as a query parameter
        $activationLink = URL::temporarySignedRoute(
            'user.activate', // The route name for user activation
            Carbon::now()->addMinutes(60), // Link expiration time (1 hour)
            ['id' => $this->user->id,'model_name' => class_basename($verification)], // Pass the user ID to the route
        ) ;

        // Prepare the message content
        $message = "Thank you for registering on Busaty App. Your activation code is <b>{$this->verificationCode}</b><br>";
        $message .= "To activate your account, please click the link below:<br>";
        $message .= "<a href='{$activationLink}'>Activate Your Account</a>";

        // Prepare the mail data
        $mailData = [
            'recipient' => $this->user->email,
            'subject' => 'Email Verification',
            'body' => $message,
            'code' => $this->verificationCode,
            'activation_link' => $activationLink, // Include the activation link
            'model_name' => $this->modelName, // Pass the model name
        ];

        // Return the email to be sent
        return (new SenVerificationCode($mailData))->to($this->user->email);
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'verification_code' => $this->verificationCode, // Store the verification code in the array
            'model_name' => $this->modelName, // Store the model name
        ];
    }
}
