<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\MailServiceProvider::class,

    // ✅ Only if needed manually:
    Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
];
