<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ Global Middleware
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->append(\App\Http\Middleware\TrustProxies::class);
        $middleware->append(\App\Http\Middleware\PreventRequestsDuringMaintenance::class);
        $middleware->append(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
        $middleware->append(\App\Http\Middleware\TrimStrings::class);
        $middleware->append(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);

        // ✅ Web Group Middleware
        $middleware->web(append: [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ✅ API Group Middleware
        $middleware->api(append: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        // ✅ Route Middleware Aliases
        $middleware->alias([
            'auth'               => \App\Http\Middleware\Authenticate::class,
            'auth.basic'         => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers'      => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'                => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'              => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm'   => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'             => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'           => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'           => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // ✅ Spatie Permissions
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // ✅ Custom Middleware
            'is_school'          => \App\Http\Middleware\isSchool::class,
            'is_parents'         => \App\Http\Middleware\isParents::class,
            'is_attendants'      => \App\Http\Middleware\isAttendants::class,
            'attendants_active'  => \App\Http\Middleware\AttendantsAactive::class,
            'is_verify'          => \App\Http\Middleware\IsUserVerifyEmail::class,
            'cors.tracking'      => \App\Http\Middleware\CorsForTrackingApi::class,
        ]);

        // ✅ Optional: Execution Priority
        $middleware->priority([
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
