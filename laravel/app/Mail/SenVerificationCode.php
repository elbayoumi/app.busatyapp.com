<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class SenVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Mail payload.
     * Expected keys: ['email', 'code', 'activation_link', 'token', 'tracking_pixel'?]
     */
    public array $mail_data;

    /**
     * Constructor to inject payload.
     */
    public function __construct(array $mail_data)
    {
        $this->mail_data = $mail_data;
    }

    /**
     * Build a deliverability-friendly verification email.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Email Verification')
                    ->view('emails.verification-code')
                    ->with([
                        'mail_data' => $this->mail_data,
                    ]);
    }

}
