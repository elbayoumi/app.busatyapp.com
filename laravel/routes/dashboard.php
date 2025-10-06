<?php

use App\Http\Controllers\Api\Attendant\MessagesParents\MessageController;
use App\Http\Controllers\Dashboard\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\{
    AuthController,
    HomeController,
    ProfileController,
    StaffController,
    ParentConteoller,
    AttendantController,
    AttendanceController,
    Buscontroler,
    GradeController,
    SettingsController,
    RolesController,
    AjaxController,
    ClassroomController,
    SchoolController,
    AbsenceControler,
    AddressController,
    TripControler,
    SchoolMessageController,
    subscriptions\Subscription,
    AdesController,
    AppController,
    ContactController,
    LogViewerController,
    NotificationTextController,
    ProjectController,
    QuestionController,
    TripTypeController
};
use App\Http\Controllers\Dashboard\coupons\CouponController;
use App\Http\Controllers\GlobalSearchController;
use App\Mail\{
    OrderShipped,
    TestEmail,
    TestMail,
    bus
};
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

Route::prefix('dashboard')->group(function () {
    Route::get('/api', function () {
        // تحديد مسار الملف
        $doc = asset('storage/api/api.json'); // تأكد من امتداد الملف (على سبيل المثال: api.json)
        return view('api', ['fileName' => $doc]);
    });
    Route::get('/', function () {
        return view('welcome');
    });
    // Route::get('/pdf', function () {
    //     $bus = bus::where("id", 1)->first();
    //     dd($bus);
    //     return view('dashboard.buses.pdf', [
    //         'bus' => $bus,
    //     ]);
    // });
    Route::get('login', [AuthController::class, 'login_page'])->middleware('guest');
    Route::post('login', [AuthController::class, 'login'])->middleware('guest')->name('dashboard.login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:web')->name('dashboard.logout');
    // ✅ New Register routes for Parent and School

});
// Dashboard Register Routes// routes/web.php
Route::prefix('dashboard')->group(function () {
    Route::get('register/parent', [AuthController::class, 'showParentRegisterForm'])->name('parent.register.form');
Route::post('register/parent', [AuthController::class, 'registerParent'])->name('parent.register');

Route::get('register/school', [AuthController::class, 'showRegisterForm'])->name('school.register.form');
Route::post('register/school', [AuthController::class, 'register'])->name('school.register');


    Route::get('school/verify', [AuthController::class, 'showVerifyForm'])->name('school.verify.form');
    Route::post('school/verify', [AuthController::class, 'verify']);

    Route::get('school/login', [AuthController::class, 'showLoginForm'])->name('school.login.form');
    Route::post('school/login', [AuthController::class, 'login']);

    Route::post('school/logout', [AuthController::class, 'logout'])->name('school.logout');

    Route::get('school/forgot-password', [AuthController::class, 'showForgotForm'])->name('school.forgot.form');
    Route::post('school/forgot-password', [AuthController::class, 'forgot_password']);
    Route::post('school/reset-password', [AuthController::class, 'reset_password']);
});

Route::get('forgot-password', [AuthController::class, 'forgot_password_page'])->middleware('guest')->name('password.request');
Route::post('forgot-password', [AuthController::class, 'forgot_password'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'reset_password_token'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset_password'])->middleware('guest')->name('password.update');

Route::middleware(['auth:web'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('app', AppController::class)->parameters([
        'app' => 'app' // Ensures Laravel treats UUID properly
    ]);

    // routes/web.php
    // Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');
    Route::get('//global-search', [GlobalSearchController::class, 'globalSearch'])->name('global-search');

    Route::post('/run-pm2', function () {
        try {
            Artisan::call('run:pm2');
            return response()->json(['message' => 'PM2 started successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });


    Route::get('/send-mail', function () {
        Mail::to('newuser@example.com')->send(new TestMail());
        return 'A message has been sent to Mailtrap!';
    });
    Route::get('/sockit', function () {


        return view('webtest');
    })->name('socket.io');
    Route::resource('notifications', NotificationTextController::class);
    Route::get('notification/send', [NotificationTextController::class, 'send'])->name('notification.send');

    Route::resource('question', QuestionController::class);
    Route::resource('trip-type', TripTypeController::class);
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::get('settings/general', [SettingsController::class, 'general_settings_edit'])->name('settings.general');
    Route::put('settings/general/update', [SettingsController::class, 'general_settings_update'])->name('settings.general.update');
    Route::get('settings/distributors-app', [SettingsController::class, 'distributors_app_edit'])->name('settings.distributors-app');
    Route::put('settings/distributors-app/update', [SettingsController::class, 'distributors_app_update'])->name('settings.distributors-app.update');
    Route::get('settings/smtp', [SettingsController::class, 'smtp'])->name('settings.smtp');
    Route::put('settings/smtp/update', [SettingsController::class, 'smtp_update'])->name('settings.smtp.update');
    Route::post('settings/smtp/test', [SettingsController::class, 'send_test_email'])->name('settings.smtp.test');

    Route::resource('roles', RolesController::class);

    Route::get('dark-mode-update', [ProfileController::class, 'dark_mode_update'])->name('profile.dark-mode-update');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/{id}/info-update', [ProfileController::class, 'info_update'])->name('profile.info-update');

    Route::resource('staff', StaffController::class);
    Route::put('staff/{id}/update-info', [StaffController::class, 'update_info'])->name('staff.update-info');




    Route::resource('schools', SchoolController::class);
    Route::get('schools/grade-schools/{id}', [SchoolController::class, 'gradeSchools'])->name('schools.grade-schools');


    Route::resource('grades', GradeController::class);
    Route::get('grades/get-grades/{id}', [GradeController::class, 'getGrades']);

    Route::prefix('classrooms')->name('classrooms.')->group(function () {

        Route::get('/create/{id}',    [ClassroomController::class, 'create'])->name('create');
        Route::post('store',          [ClassroomController::class, 'store'])->name('store');
        Route::get('/index',          [ClassroomController::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [ClassroomController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [ClassroomController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [ClassroomController::class, 'update'])->name('update');
        Route::get('/show/{id}',      [ClassroomController::class, 'show'])->name('show');
        Route::get('/get-classrooms/{id}', [ClassroomController::class, 'getClassrooms']);
        Route::get('/grade-classrooms/{id}',      [ClassroomController::class, 'gradeClassrooms'])->name('grade-classrooms');
    });
    Route::prefix('attendants')->name('attendants.')->group(function () {

        Route::get('/create',         [AttendantController::class, 'create'])->name('create');
        Route::post('store',          [AttendantController::class, 'store'])->name('store');
        Route::get('/index',          [AttendantController::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [AttendantController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [AttendantController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [AttendantController::class, 'update'])->name('update');
        Route::get('/show/{id}',      [AttendantController::class, 'show'])->name('show');
        Route::get('/get-driver/{id}', [AttendantController::class, 'getDriver']);
        Route::get('/get-admins/{id}', [AttendantController::class, 'getAdmins']);
    });

    Route::prefix('parents')->name('parents.')->group(function () {

        Route::get('/create',         [ParentConteoller::class, 'create'])->name('create');
        Route::post('store',          [ParentConteoller::class, 'store'])->name('store');
        Route::get('/index',          [ParentConteoller::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [ParentConteoller::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [ParentConteoller::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [ParentConteoller::class, 'update'])->name('update');
        Route::get('/show/{id}',      [ParentConteoller::class, 'show'])->name('show');
        Route::get('/add-child/{id}',      [ParentConteoller::class, 'addChild'])->name('add-child');
        Route::post('/store-child/{id}',      [ParentConteoller::class, 'storeChild'])->name('store-child');
        Route::get('/child/{id}',      [ParentConteoller::class, 'child'])->name('child');
        Route::get('/child-show/{id}',      [ParentConteoller::class, 'childShow'])->name('child-show');
        // Route::get('/school-parents/{id}',      [ParentConteoller::class, 'schoolParents'])->name('school-parents');
    });

    Route::prefix('buses')->name('buses.')->group(function () {

        Route::get('/create',         [Buscontroler::class, 'create'])->name('create');
        Route::post('store',          [Buscontroler::class, 'store'])->name('store');
        Route::get('/index',          [Buscontroler::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [Buscontroler::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [Buscontroler::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [Buscontroler::class, 'update'])->name('update');
        Route::get('/show/{id}',      [Buscontroler::class, 'show'])->name('show');
        Route::get('/get-bus/{id}',   [Buscontroler::class, 'getBus']);
        Route::get('/school-buses/{id}',   [Buscontroler::class, 'schoolBuses'])->name('school-buses');
        Route::get('/print/data/{id}',   [Buscontroler::class, 'print_data'])->name('print.data');
        Route::get('/export/data/all/{id}',   [Buscontroler::class, 'bus_export'])->name('export.data');
        Route::get('/export/data/pdf/{id}',   [Buscontroler::class, 'bus_export_pdf'])->name('export.data.pdf');
    });

    Route::prefix('students')->name('students.')->group(function () {

        Route::get('/create',         [StudentController::class, 'create'])->name('create');
        Route::post('store',          [StudentController::class, 'store'])->name('store');
        Route::get('/index',          [StudentController::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [StudentController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [StudentController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [StudentController::class, 'update'])->name('update');
        Route::get('/show/{id}',      [StudentController::class, 'show'])->name('show');
        Route::get('/classrooms-students/{id}',      [StudentController::class, 'classroomStudent'])->name('classrooms-students');
        Route::get('/grade-students/{id}',      [StudentController::class, 'gradeStudent'])->name('grade-students');
        Route::get('/admins-students/{id}',      [StudentController::class, 'adminsStudents'])->name('admins-students');
        Route::get('/driver-students/{id}',      [StudentController::class, 'driverStudents'])->name('driver-students');
        Route::get('export/data', [StudentController::class, 'students_data_export'])->name('export-data');
        Route::post('export/', [StudentController::class, 'students_export'])->name('export');
        Route::get('/get-students/{id}',   [StudentController::class, 'getStudents']);
        Route::get('/add-to-bus',   [StudentController::class, 'addToBus'])->name('add.to.bus');
        Route::post('/store-to-bus',   [StudentController::class, 'storeToBus'])->name('store.to.bus');
        Route::get('/get-students-school/{id}',   [StudentController::class, 'getStudentsSchool']);
        Route::get('upload', [StudentController::class, 'upload'])->name('upload');
        Route::post('import', [StudentController::class, 'students_import'])->name('import');
        Route::get('/attachments/download/import/sheet', [StudentController::class, 'download'])->name('attachments.import');
        Route::get('attendants/send/messgees/{id}',      [StudentController::class, 'attendants_send_messgees'])->name('attendants.send.messgees');
        Route::post('attendants/send/messgees/{id}',      [StudentController::class, 'attendants_send_messgees_store'])->name('attendants.send.messgees.store');



        Route::get('/send-mail', function () {
            Mail::to('newuser@example.com')->send(new OrderShipped());
            return 'A message has been sent to Mailtrap!';
        });
    });


    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('end/{id}',          [AttendanceController::class, 'create_end'])->name('get.end');
        Route::post('store/{id}',          [AttendanceController::class, 'store'])->name('store');
        Route::get('index/{id}',          [AttendanceController::class, 'index'])->name('index');
        Route::post('end/{id}',          [AttendanceController::class, 'end'])->name('end');
        Route::delete('destroy/{id}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::put('/update/{id}',    [AttendanceController::class, 'update'])->name('update');
        Route::get('show/{id}',      [AttendanceController::class, 'show'])->name('show');
        Route::get('create/{id}',          [AttendanceController::class, 'create'])->name('create');
        Route::get('students/end/{id}',          [AttendanceController::class, 'attendances_trip_students_end'])->name('students.end');
    });



    Route::prefix('absences')->name('absences.')->group(function () {

        Route::get('/create',         [AbsenceControler::class, 'create'])->name('create');
        Route::post('store',          [AbsenceControler::class, 'store'])->name('store');
        Route::get('/index',          [AbsenceControler::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [AbsenceControler::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [AbsenceControler::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [AbsenceControler::class, 'update'])->name('update');
        Route::get('show/{id}',      [AbsenceControler::class, 'show'])->name('show');
    });



    Route::prefix('addresses')->name('addresses.')->group(function () {

        Route::get('/create/{id}',         [AddressController::class, 'create'])->name('create');
        Route::post('store/{id}',          [AddressController::class, 'store'])->name('store');
        Route::get('/index',          [AddressController::class, 'index'])->name('index');
        Route::get('accepted/{id}', [AddressController::class, 'accepted'])->name('accepted');
        Route::delete('destroy/{id}', [AddressController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [AddressController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [AddressController::class, 'update'])->name('update');
        Route::get('/show/{id}',      [AddressController::class, 'show'])->name('show');
        Route::get('/unaccepted/{id}',      [AddressController::class, 'unaccepted'])->name('unaccepted');
    });

    Route::prefix('trips')->name('trips.')->group(function () {

        Route::get('index',          [TripControler::class, 'index'])->name('index');
        Route::get('edit/{id}',      [TripControler::class, 'edit'])->name('edit');
        Route::get('show/{id}',      [TripControler::class, 'show'])->name('show');
        Route::put('update/{id}',    [TripControler::class, 'update'])->name('update');
        Route::get('create/{id}',    [TripControler::class, 'create'])->name('create');
        Route::post('store/{id}',    [TripControler::class, 'store'])->name('store');
        Route::delete('destroy/{id}', [TripControler::class, 'destroy'])->name('destroy');
        Route::get('end/{id}',    [TripControler::class, 'end'])->name('end');
        Route::get('map/{id}',      [TripControler::class, 'showOnMap'])->name('map');
    });


    Route::prefix('school_messages')->name('school_messages.')->group(function () {

        Route::get('/create',         [SchoolMessageController::class, 'create'])->name('create');
        Route::post('store',          [SchoolMessageController::class, 'store'])->name('store');
        Route::get('/index',          [SchoolMessageController::class, 'index'])->name('index');
        Route::delete('destroy/{id}', [SchoolMessageController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}',      [SchoolMessageController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',    [SchoolMessageController::class, 'update'])->name('update');
        Route::get('/show/{id}',      [SchoolMessageController::class, 'show'])->name('show');
    });
    Route::resource('subscription', Subscription::class);
    Route::resource('coupon', CouponController::class);
    Route::get('coupon/{coupon}/users', [CouponController::class, 'users'])->name('coupon.users');

    Route::resource('adesSchool', AdesController::class);
    Route::post('adesSchool/storeSchooleToAdes/{schoolId}/{adesId}/{ades_to}', [AdesController::class, 'storeSchooleToAdes'])
        ->name('adesSchool.storeSchooleToAdes');
    Route::delete('adesSchool/removeSchooleToAdes/{adesSchoollId}', [AdesController::class, 'removeSchooleToAdes'])
        ->name('adesSchool.removeSchooleToAdes');
    Route::get('adesSchool/showSchools/{id}', [AdesController::class, 'showSchools'])->name('adesSchool.showSchools');
    Route::get('/logger', [LogViewerController::class, 'index'])->name('logger.index');
    Route::get('/logger/clear', [LogViewerController::class, 'clearLogs'])->name('logger.clear');
    Route::get('/mqtt', function () {
        return view('dashboard.mqtt.index');
    })->name('mqtt.index');
    Route::resource('projects', ProjectController::class);
    Route::get('/dashboard/projects/json', [ProjectController::class, 'getProjectsJson'])->name('projects.json');
    Route::get('/dashboard/project/json/{id}', [ProjectController::class, 'getProjectJson'])->name('project.json');
});

Route::post('/contact/send', [ContactController::class, 'send'])->name('api.contact.send');
