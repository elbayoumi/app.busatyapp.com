<?php

use Illuminate\{
    Http\Request,
    Support\Facades\Route
};

use App\Http\Controllers\Api\Attendant\{
    UserController,
    HomeController,
    Auth\AuthController,
    MessagesParents\MessageController,
    AdesController,
};

use App\Http\Controllers\Api\Attendant\Trips\{
    TripsController,
    absences\AbsencesController,
    attendance\AttendanceController,
    TripDayAttendanceController,
    TripDayAttendantController,
};
use App\Http\Controllers\Api\ErrorController;
use App\Http\Controllers\Api\NotificationController;

Route::post('login', [AuthController::class, 'login']);



Route::middleware(['auth:attendant', 'is_attendants'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('data/update', [UserController::class, 'data_update']);
    Route::post('password/update', [UserController::class, 'password_update']);

    Route::middleware(['attendants_active'])->group(function () {
        Route::get('messages/static', [MessageController::class, 'messageStatic']);
        Route::get('my/bus', [HomeController::class, 'myBus']);
        Route::get('my/students/all', [HomeController::class, 'myStudents']);
        Route::get('my/students/show/{id}', [HomeController::class, 'myStudentsShow']);

        Route::get('my/students/parents/all', [HomeController::class, 'myStudentsParents']);
        Route::get('my/student/parents/all/{id}', [HomeController::class, 'myStudentsParentsShow']);
        Route::get('my/student/parent/show/{id}', [HomeController::class, 'ParentsShow']);
        Route::prefix('absences')->group(function () {
            Route::get('index', [AbsencesController::class, 'index']);
            Route::get('day/all', [HomeController::class, 'absencesDeyShow']);
            Route::get('day/show/{id}', [HomeController::class, 'absencesShow']);
        });



        Route::prefix('trips')->group(function () {
            Route::get('notifylist', [TripsController::class, 'notifyList']);

            Route::post('off/{id}', [TripsController::class, 'off']);
            //waitingToSchool
            Route::prefix('morning')->group(function () {
                Route::get('status', [TripsController::class, 'statusEndTripFromHome']);
                Route::post('start', [TripsController::class, 'storeStartTripFromHome']);
                Route::post('end', [TripsController::class, 'arrivedToSchoolStartTripFromHome']);
                Route::get('waiting', [TripsController::class, 'showCurrentWaitingToSchool']);

                Route::get('onBus', [TripsController::class, 'showCurrentPresentOnBusToSchool']);
                Route::get('absences', [TripsController::class, 'showCurrentAbsencessStartDay']);
                Route::post('absent/{student_id}', [AbsencesController::class, 'storeAbsenceFromHome']);

                Route::post('present_on_bus/{student_id}', [AttendanceController::class, 'present_on_bus_start_day']);
                Route::post('removePresent_on_bus', [TripsController::class, 'removePresentOnBusToSchool']);
                Route::post('removeAbsenceToSchool', [TripsController::class, 'removeAbsenceToSchool']);
                Route::prefix('parents/messages')->group(function () {

                    Route::get('create', [MessageController::class, 'getCreateMessageStartDay']);
                    Route::post('store/{student_id}', [MessageController::class, 'getStoreStartDay']);
                    Route::post('storeForAll', [MessageController::class, 'getStoreStartDayForAll']);
                });
            });

            Route::prefix('evening')->group(function () {
                Route::get('status', [TripsController::class, 'statusEndTripFromSchool']);
                Route::post('start', [TripsController::class, 'storeEndTripFromSchool']);
                Route::post('endTrip', [TripsController::class, 'arrivedToHomeEndTripFromSchool']);
                Route::post('arrivedStudent/{student_id}', [TripsController::class, 'arrivedStudentToHomeEndTripFromSchool']);
                Route::post('removeArrivedStudent/{student_id}', [TripsController::class, 'removeArrivedStudentToHomeEndTripFromSchool']);
                Route::get('onBus/{trip_id}', [TripsController::class, 'showCurrentPresentOnBusToHome']);
                Route::get('arrived', [TripsController::class, 'showCurrentArrivedToHome']);
                Route::get('absences', [TripsController::class, 'showCurrentAbsencessEndDay']);
                Route::post('absent/{student_id}', [AbsencesController::class, 'storeAbsenceFromSchool']);
                Route::post('removeAbsenceToHome/{student_id}', [TripsController::class, 'removeAbsenceToHome']);
                Route::prefix('parents/messages')->group(function () {
                    Route::get('create', [MessageController::class, 'getCreateMessageEndDay']);
                    Route::post('store/{student_id}', [MessageController::class, 'getStoreEndtDay']);
                    Route::post('storeForAll', [MessageController::class, 'getStoreEndDayForAll']);
                });
            });



            Route::get('all', [TripsController::class, 'getAll']);
            Route::get('show/{id}', [TripsController::class, 'getShow']);
            Route::get('today/start', [TripsController::class, 'getStartTripFromDay']);
            Route::get('today/end', [TripsController::class, 'getEndTripFromDay']);






            Route::prefix('trip-days/student/{tripDay}/attendances')->controller(TripDayAttendanceController::class)->group(function () {
                Route::get('/', 'index');


                Route::post('/check-in', 'checkIn');
                Route::post('/check-out', 'checkOut');
                Route::post('/mark-absent', 'markAbsent');
                Route::post('/mark-excused', 'markExcused');
            });
        });

        Route::prefix('trip-days/{tripDay}')
            ->name('trip-days.')
            ->group(function () {
                Route::get('attendants',        [TripDayAttendantController::class, 'index'])->name('attendants.index');
                Route::post('attendants/join',  [TripDayAttendantController::class, 'join'])->name('attendants.join');
                Route::post('attendants/leave', [TripDayAttendantController::class, 'leave'])->name('attendants.leave');

                // Optional RESTful show/destroy on specific link:
                Route::get('attendants/{tripDayAttendant}',    [TripDayAttendantController::class, 'show'])->name('attendants.show');
                Route::delete('attendants/{tripDayAttendant}', [TripDayAttendantController::class, 'destroy'])->name('attendants.destroy');
            });


        Route::prefix('trip/attendances')->group(function () {
            Route::get('index/{id}', [AttendanceController::class, 'index']);
            Route::get('show/{id}', [AttendanceController::class, 'show']);
            Route::get('create/{id}', [AttendanceController::class, 'create']);
            Route::post('store/{id}', [AttendanceController::class, 'store']);
            Route::get('edit/{id}', [AttendanceController::class, 'create']);
            Route::post('update/{id}', [AttendanceController::class, 'update']);
            Route::post('end/{id}', [AttendanceController::class, 'end']);
        });
        Route::apiResource('notifications', NotificationController::class, ['as' => 'attendants']);
        Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);

        Route::get('ades', [AdesController::class, 'index']);
    });
});
