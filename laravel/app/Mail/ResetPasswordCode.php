<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCode extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_data;

    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('account reset password')
        ->view('schools-password-code')->with([
            'mail_data' => $this->mail_data,
        ]);
    }
}
