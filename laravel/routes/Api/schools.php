<?php

use App\Http\Controllers\Api\schools\addresses\TemporaryAddressController;
use Illuminate\{
    Http\Request,
    Support\Facades\Route
};


use App\Http\Controllers\Api\{
    ErrorController,
    NotificationController,
    //start school controllers api routes
    schools\grade\GradeController,
    schools\General\GeneralController,
    schools\classrooms\ClassroomController,
    schools\attendants\AttendantController,
    schools\buses\BusesController,
    schools\students\StudentController,
    schools\parents\ParentsController,
    schools\addresses\AddressesController,
    schools\UserController as SchoolController,
    schools\Auth\AuthController,
    schools\schoolMessages\SchoolMessagesController,
    schools\AdesController as AdesSchoolController,

    schools\absences\AbsenceControler,
    schools\trips\attendance\AttendanceController,
    schools\trips\TripsController,
    //end school controllers api
};
use App\Http\Controllers\Api\schools\trips\TripController;
use Carbon\Carbon;

Route::post('register/{lang_default_classrooms_ar?}', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::delete('delete', [AuthController::class, 'deleteAccount']);

Route::middleware(['auth:school', 'is_school'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/resendCode', [AuthController::class, 'resendCode']);

    Route::get('/user', function (Request $request) {

        return $request->user()->load([
            'subscriptions' => function ($q) {
                $q->where('end_date', '>=', Carbon::now());
            }
        ]);
    });

    Route::middleware(['is_verify'])->group(function () {
        Route::post('data/complete', [SchoolController::class, 'complete']);

        Route::prefix('general')->group(function () {

            Route::post('data/update', [SchoolController::class, 'data_update']);
            Route::post('notification/settings', [SchoolController::class, 'notificationSettings']);
            Route::post('password/update', [SchoolController::class, 'password_update']);

            Route::get('show', [GeneralController::class, 'getSchool']);
            Route::get('grades/show', [GeneralController::class, 'getGrades']);
            Route::get('grade/show/{id}', [GeneralController::class, 'getGrade']);

            Route::get('classrooms/all', [GeneralController::class, 'getClassrooms']);
            Route::get('classrooms/show/{id}', [GeneralController::class, 'getClassroom']);


            Route::get('attendants/all', [GeneralController::class, 'getAttendants']);
            Route::get('attendants/show/{id}', [GeneralController::class, 'getAttendant']);

            //end_day or start_day {id}

            Route::get('fixedtrip/all', [GeneralController::class, 'getFixedTrip']);
            Route::get('trips', [GeneralController::class, 'trip']);
            Route::get('trips/routes/{id}', [GeneralController::class, 'routes']);
            Route::get('trips/absents/{trip_id}', [TripsController::class, 'showPreviousTripAbsents']);
            Route::get('trips/attendants/{trip_id}', [TripsController::class, 'showPreviousTripAttendants']);
            //end_day or start_day {id}
            Route::post('fixedtrip/update/{id?}', [GeneralController::class, 'updateFixedTrip']);


            Route::get('students/all', [GeneralController::class, 'getStudents']);
            Route::get('students/show/{id}', [GeneralController::class, 'getStudent']);

            Route::get('parents/all', [GeneralController::class, 'getParents']);
            Route::get('parents/show/{id}', [GeneralController::class, 'getParent']);


            Route::get('buses/all', [GeneralController::class, 'getBuses']);
            Route::get('buses/show/{id}', [GeneralController::class, 'getBus']);

            Route::get('religion', [GeneralController::class, 'getReligion']);
            Route::get('gender', [GeneralController::class, 'getGender']);
            Route::get('type/blood', [GeneralController::class, 'getType_Blood']);
            Route::get('ades', [AdesSchoolController::class, 'index']);
        });
        Route::prefix('grades')->group(function () {

            Route::get('all', [GradeController::class, 'getAll']);
            Route::get('school/grade', [GradeController::class, 'getSchoolGrade']);
            Route::get('show/{id}', [GradeController::class, 'getShow']);
            Route::post('store', [GradeController::class, 'getStore']);
            Route::delete('destroy/{id}', [GradeController::class, 'delete_grade']);
        });

        Route::prefix('classrooms')->group(function () {

            Route::get('index', [ClassroomController::class, 'index']);
            Route::get('show/{id}', [ClassroomController::class, 'getShow']);
            Route::post('store', [ClassroomController::class, 'getStore']);
            Route::post('update/{id}', [ClassroomController::class, 'update_classroom']);
            Route::delete('destroy/{id}', [ClassroomController::class, 'delete_classroom']);
            Route::get('create', [ClassroomController::class, 'create']);
            Route::get('edit/{id}', [ClassroomController::class, 'edit']);
            Route::get('grade/classroom/{id}', [ClassroomController::class, 'getClassrooms']);
        });


        Route::prefix('attendants')->group(function () {

            Route::get('all/driver', [AttendantController::class, 'getAllDriver']);
            Route::get('all/admins', [AttendantController::class, 'getAllAdmins']);

            Route::get('show/driver/{id}', [AttendantController::class, 'getShowDriver']);
            Route::get('show/admins/{id}', [AttendantController::class, 'getShowAdmins']);

            Route::get('create/driver', [AttendantController::class, 'create_driver']);
            Route::get('create/admins', [AttendantController::class, 'create_admins']);

            Route::post('store/driver', [AttendantController::class, 'getStoreDriver']);
            Route::post('store/admins', [AttendantController::class, 'getStoreAdmins']);


            Route::get('edit/driver/{id}', [AttendantController::class, 'edit_drive']);
            Route::get('edit/admins/{id}', [AttendantController::class, 'edit_admins']);


            Route::post('update/driver/{id}', [AttendantController::class, 'update_driver']);
            Route::post('update/admins/{id}', [AttendantController::class, 'update_admins']);

            Route::delete('destroy/{id}', [AttendantController::class, 'delete']);

            Route::get('search/driver', [AttendantController::class, 'search_drivers']);
            Route::get('search/admins', [AttendantController::class, 'search_admins']);
        });


        Route::prefix('buses')->group(function () {

            Route::get('all/{param?}', [BusesController::class, 'getAll']);
            Route::get('show/{id}', [BusesController::class, 'getShow']);
            Route::prefix('show')->group(function () {
                Route::prefix('availableAdd')->group(function () {
                    Route::get('/driver', [BusesController::class, 'availableAddDriver']);
                    Route::get('/admins', [BusesController::class, 'availableAddAdmins']);
                });
            });
            Route::get('showStudentsDNE/{id}', [BusesController::class, 'getShowStudentsDosNotExistInBus']);

            Route::get('create', [BusesController::class, 'create_bus']);
            Route::post('store', [BusesController::class, 'getStore']);
            Route::get('edit/{id}', [BusesController::class, 'edit']);

            Route::post('update/{id}', [BusesController::class, 'update_bus']);
            Route::delete('destroy/{id}', [BusesController::class, 'delete_bus']);
            Route::get('search', [BusesController::class, 'search']);

            Route::post('remove/students', [BusesController::class, 'removeStudentsToBus']);
            Route::get('add/students', [BusesController::class, 'addStudentsToBus']);
            Route::post('store/students', [BusesController::class, 'storeStudentsToBus']);

            Route::get('add/student', [BusesController::class, 'addStudentToBus']);
            Route::get('empty/students', [BusesController::class, 'filterStudentsByBusAndClassroom']);
            Route::post('store/student', [BusesController::class, 'storeStudentToBus']);
            Route::get('/export/data/pdf/{id?}/{ln?}', [BusesController::class, 'bus_export_pdf']);
        });



        Route::prefix('messages')->group(function () {


            Route::get('all', [SchoolMessagesController::class, 'getAll']);
            Route::get('show/{id}', [SchoolMessagesController::class, 'getShow']);
            Route::post('store', [SchoolMessagesController::class, 'getStore']);
        });
        Route::apiResource('notifications', NotificationController::class, ['as' => 'schools']);

        Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);


        Route::prefix('students')->group(function () {

            Route::get('index/{bus_id?}', [StudentController::class, 'index']);
            Route::post('store', [StudentController::class, 'getStore']);
            Route::post('update/{id}', [StudentController::class, 'update_student']);
            Route::delete('destroy/{id}', [StudentController::class, 'delete_student']);
            Route::get('search', [StudentController::class, 'search']);
            Route::get('export/data', [StudentController::class, 'students_data_export']);
            Route::post('export/{ln?}', [StudentController::class, 'students_export']);
            Route::get('create', [StudentController::class, 'create']);
            Route::get('edit/{id}', [StudentController::class, 'edit']);
            Route::get('upload', [StudentController::class, 'upload']);
            Route::get('download', [StudentController::class, 'download']);
            Route::get('download/show', [StudentController::class, 'downloadShow']);

            Route::post('import', [StudentController::class, 'students_import']);

            Route::prefix('parent')->group(function () {
                Route::get('indexStudentParent', [StudentController::class, 'indexStudentParent']);
                Route::get('indexStudentDosntHaveParent', [StudentController::class, 'indexStudentDosntHaveParent']);
                Route::get('indexStudentDosntHaveAddress', [StudentController::class, 'indexStudentDosntHaveAddress']);
                Route::get('indexStudentHasAddress', [StudentController::class, 'indexStudentHasAddress']);

                Route::get('all/{id}', [StudentController::class, 'parentAll']);
                Route::get('show/{id}', [StudentController::class, 'parentShow']);
            });
        });


        Route::prefix('parents')->group(function () {

            Route::get('index', [ParentsController::class, 'index']);
            Route::get('show/{id}', [ParentsController::class, 'show']);
            Route::get('indexDosntHaveStudent', [ParentsController::class, 'indexDosntHaveStudent']);
        });


        Route::prefix('absences')->group(function () {

            Route::get('index', [AbsenceControler::class, 'index']);
            Route::get('show/{id}', [AbsenceControler::class, 'show']);
        });

        Route::prefix('addresses')->group(function () {
            Route::get('index', [AddressesController::class, 'index']);
            Route::get('show/{id}', [AddressesController::class, 'show']);
            Route::get('accepted/{id}', [AddressesController::class, 'accepted']);
            Route::get('unaccepted/{id}', [AddressesController::class, 'unaccepted']);
        });

        Route::prefix('temporary-addresses')->group(function () {
            Route::get('/', [TemporaryAddressController::class, 'index']);
            Route::get('status', [TemporaryAddressController::class, 'status']);
            Route::post('change-bus/{temporaryAddress:id}', [TemporaryAddressController::class, 'changeBus']);
            Route::patch('respond-to-request/{temporaryAddress:id}', [TemporaryAddressController::class, 'schoolResponse']);
        });

        Route::apiResource('trips', TripController::class);
        Route::post('/trips/transfer-student', [TripController::class, 'transferStudent']);

        Route::prefix('trips/{trip}')->group(function () {
            Route::post('bus', [TripController::class, 'assignBus']);
            Route::post('attendants', [TripController::class, 'attachAttendants']);

            Route::post('students', [TripController::class, 'attachStudents']);
            Route::delete('students/{studentId}',  [TripController::class, 'detachStudent']);
            Route::post('students/detach',         [TripController::class, 'detachStudents']);   // bulk
            // Attendants attach/detach
            Route::delete('attendants/{attendantId}', [TripController::class, 'detachAttendant']);
            Route::post('attendants/detach',          [TripController::class, 'detachAttendants']); // bulk

        });

        Route::prefix('trips')->group(function () {
            Route::get('current', [TripsController::class, 'current']);
            Route::get('currentByBus/{bus_id}', [TripsController::class, 'currentByBus']);


            Route::prefix('previous')->group(function () {
                Route::get('index', [TripsController::class, 'showPreviousTrips']);
                // trip routes & student info will be the same as current trip & exists up in this file in general
            });

            Route::prefix('morning')->group(function () {
                Route::get('waiting/{bus_id}', [TripsController::class, 'showCurrentWaitingToSchool']);
                Route::get('onbus/{bus_id}', [TripsController::class, 'showCurrentPresentOnBusToSchool']);
                Route::get('absences/{bus_id}', [TripsController::class, 'showCurrentAbsencessStartDay']);
            });

            Route::prefix('evening')->group(function () {
                Route::get('onbus/{trip_id}', [TripsController::class, 'showCurrentPresentOnBusToHome']);
                Route::get('arrived/{bus_id}', [TripsController::class, 'showCurrentArrivedToHome']);
                Route::get('absences/{bus_id}', [TripsController::class, 'showCurrentAbsencessEndDay']);
            });

            Route::get('index', [TripsController::class, 'index']);
            Route::get('show/{id}', [TripsController::class, 'show']);
            Route::get('map/show/{id}', [TripsController::class, 'getTripOnMap']);
            Route::post('update/{id}', [TripsController::class, 'update']);
            Route::post('end/{id}', [TripsController::class, 'end']);
        });
        // new live



        Route::prefix('attendances')->group(function () {

            Route::get('index/{id}', [AttendanceController::class, 'index']);
            Route::get('show/{id}', [AttendanceController::class, 'show']);
            Route::post('update/{id}', [AttendanceController::class, 'update']);
            Route::post('end/{id}', [AttendanceController::class, 'end']);
        });
    });
});


Route::post('forgot-password', [AuthController::class, 'forgot_password']);
Route::post('/reset-password', [AuthController::class, 'reset_password']);
