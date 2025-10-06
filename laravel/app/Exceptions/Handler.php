<?php

namespace App\Exceptions;

use App\Mail\SendErrorMessage;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->reportable(function (Throwable $e) {
            $mail_data = [
                'recipient' => 'mohamedashrafelbayoumi@gmail.com',
                'subject' => 'Email Error',
                'body' => ', getFile():'.$e->getFile().', getLine():'.$e->getLine().', getMessage()'.$e->getMessage().', code :'.$e->getCode(),
                'code' => $e->getCode(),
            ];
        // Log::error('Failed : ' . $e);
        // if (app()->bound('log')) {
        //     \Log::error($e);
        // }

//            Mail::to($mail_data['recipient'])->send(new SendErrorMessage($mail_data));
            //
        });
    }


}
