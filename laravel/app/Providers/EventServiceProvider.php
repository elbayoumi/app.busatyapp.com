<?php

namespace App\Providers;

use App\Models\Absence;
use App\Models\Attendant;
use App\Models\Trip;
use App\Observers\AttendantObserver;
use App\Observers\TripObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\My__parent_student;
use App\Models\My_Parent;
use App\Models\Project;
use App\Models\School;
use App\Models\Student;
use App\Observers\AbsenceObserver;
use App\Observers\MyParentObserver;
use App\Observers\MyParentStudentObserver;
use App\Observers\ProjectObserver;
use App\Observers\SchoolObserver;
use App\Observers\StudentObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Trip::observe(TripObserver::class);
        My__parent_student::observe(MyParentStudentObserver::class);
        My_Parent::observe(MyParentObserver::class);
        Attendant::observe(AttendantObserver::class);
        School::observe(SchoolObserver::class);

        Project::observe(ProjectObserver::class);
        Student::observe(StudentObserver::class);
        Absence::observe(AbsenceObserver::class);


    }
}
