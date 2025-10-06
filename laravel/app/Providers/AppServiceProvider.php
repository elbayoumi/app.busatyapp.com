<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Channels\FirebaseChannel;
use App\Channels\NotifyChannel;
use App\Libs\GooglePlay\Config\GooglePlayAuthConfig;
use App\Libs\GooglePlay\GooglePlayPaymentHistory;
use App\Libs\GooglePlay\Interfaces\IGooglePlayPaymentHistory;
use App\Libs\GooglePlay\Interfaces\IJwtTokenHandler;
use App\Libs\GooglePlay\JwtTokenHandler;
use App\Models\Attendant;
use App\Models\Trip;
use App\Observers\AttendantObserver;
use App\Observers\TripObserver;
use App\Repositories\Attendant\AttendanttNotifications\NotificationRepository;
use App\Repositories\Attendant\AttendanttNotifications\NotificationRepositoryInterface;
use App\Repositories\Api\Attendant\Trips\Absences\AbsencesRepositoryInterface;
use App\Repositories\Api\Attendant\Trips\Absences\AbsencesRepository;
use App\Repositories\Api\Student\StudentRepository;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use App\Repositories\Schools\Buses\BusesInterface;
use App\Repositories\Schools\Buses\BusesRepository;
use App\Services\Firebase\FcmService;
use App\Services\TripService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);

        // Register the Firebase channel
        $this->app->singleton(FirebaseChannel::class, function ($app) {
            return new FirebaseChannel();
        });
        $this->app->singleton(NotifyChannel::class, function ($app) {
            return new NotifyChannel();
        });
        $this->app->singleton(TripService::class, function ($app) {
            return new TripService();
        });
        $this->app->singleton(FcmService::class, function ($app) {
            return new FcmService();
        });
        $this->app->bind(BusesInterface::class, BusesRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(AbsencesRepositoryInterface::class, AbsencesRepository::class);

        $this->app->bind(IJwtTokenHandler::class, function ($app) {
            return new JwtTokenHandler(
                new GooglePlayAuthConfig(
                    config('services.google_play.client_email'),
                    config('services.google_play.auth_file_path'),
                )
            );
        });


        $this->app->bind(IGooglePlayPaymentHistory::class, function ($app) {
            return new GooglePlayPaymentHistory(
                app(IJwtTokenHandler::class),
                new GooglePlayAuthConfig(
                    config('services.google_play.client_email'),
                    config('services.google_play.auth_file_path'),
                )
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
