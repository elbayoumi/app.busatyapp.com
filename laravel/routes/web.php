<?php

use App\Facades\TripFacade;
use App\Models\Trip;
use PHPUnit\Framework\Test;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\MqttService;
use Illuminate\Support\Carbon;
use App\Services\Firebase\FcmService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\PostmanController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\SiteAuthController;
use App\Models\{Attendant, My_Parent, School, Student};
use App\Http\Controllers\Api\Attendant\Trips\TripsController;
use App\Http\Controllers\Dashboard\UnsubscribeController;
use App\Mail\TestMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/backend-developers-test', [\App\Http\Controllers\TestController::class , 'test']);
Route::get('/update-migration', [\App\Http\Controllers\TestController::class , 'updateMigration']);


Route::get('/', function () {
    // $recipientEmail = 'mohamedashrafelbayoumi@gmail.com';

    // Mail::to($recipientEmail)->send(new PageEmail());
    // $user=Student::first();
    // dd($user);
    // $custom_identifiers=$user->customIdentifier->identifier;
    // dd($custom_identifiers);

    return view('welcome');
});
Route::post('/github-webhook', [WebhookController::class,'handleGithubWebhook']);

// Route::get('te',function (){
//     $json = file_get_contents('https://node.busaty.com');
// $obj = json_decode($json);
// dd($obj);
// });
Route::get('/test', function () {
    // $p=My_Parent::first()->topics()->get();
    // dd($p);

    return view('webtest');
});
Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

Route::get('/delete-account', function () {


    return view('auth.login');
});
Route::get('site-register', [SiteAuthController::class,'siteRegister']);
Route::post('site-register', [SiteAuthController::class,'siteRegisterPost']);

Route::get('fcm', [FcmController::class, 'fcm']);
Route::get('fcm-web', function (){
// مثال على إنشاء إشعار جديد
$notification = Notification::create(['data' => 'هذا هو إشعار جديد']);

// مثال على الحصول على والد (My_Parent)
$myParents = School::find(4);

// ربط الإشعار بالوالد
$myParents->setNotifications()->attach($notification->id);

    // return $myParents->getNotifications;
    $trip = Trip::with('bus.students')->latest()->first();

    $trip->load('bus.students.my_Parents');

    // Filter students based on the 'waiting' scope
    $students = $trip->bus->students->filter(function($student) use ($trip) {
        return $student->waiting($trip->attendance_type);
    });

    // Collect Firebase tokens
    $fireBaseTokens = [];

    foreach ($students as $student) {
        foreach ($student->my_Parents as $parent) {
            // Ensure $parent->fcmTokens is an array; convert it if necessary
            $tokens =  $parent->fcmTokens->pluck('fcm_token')->toArray();
            $fireBaseTokens = array_merge($fireBaseTokens, $tokens);
        }
    }
// dd($fireBaseTokens);
    // Filter out empty values and remove duplicates
    (new FcmService)->notifyByFirebase('hgfdhfdhgsh' , 'shgdfsdfhdgsh' ,array_values(array_unique(array_filter($fireBaseTokens))));
    return array_values(array_unique(array_filter($fireBaseTokens)));
    return view('dashboard.fcm');
});

Route::post('send-fcm-notification', [FcmController::class, 'sendFcmNotification']);
Route::get('notifylist', [TripsController::class, 'notifyList']);


Route::get('/publish', function(Request $request, MqttService $mqttService) {

    $mqttService->publish('test/topic',$request->input('text','Hello from Laravel!') );
    return 'Message Published!';
});

Route::get('/subscribe', function(MqttService $mqttService) {
    $mqttService->subscribe('test/topic', function($topic, $message) {
        echo "New message on topic {$topic}: {$message}\n";
    });

    return 'Subscribed to test/topic';
});




Route::get('/topic', function(Request $request) {
    $trip = Trip::latest()->first();

   return TripFacade::open($trip,My_Parent::class);

});
Route::get('/add', function(Request $request) {
    $student = Student::first();
    $current_date = Carbon::now()->format('Y-m-d');

    return $student->absences()->create([
        'school_id' => $student->school_id,
        'bus_id' => $student->bus_id,
        'attendence_date' => $current_date,
        'created_by' => $student->type,
        'attendence_type' => 'full_day',
        'my__parent_id' => optional($student->my_Parents->first())->id,
    ]);

});
// use App\Http\Controllers\PostmanCollectionController;

// Route::post('/collections/import', [PostmanCollectionController::class, 'import']);
// Route::get('/collections', [PostmanCollectionController::class, 'getAllCollections']);
// Route::get('/collections/{id}/apis', [PostmanCollectionController::class, 'getCollectionApis']);

// Route::get('/collections/import', function () {
//     return view('postman-manager'); // اسم الملف الذي سيحتوي على الـ HTML
// });

Route::get('/postman/upload', [PostmanController::class, 'showUploadForm'])->name('postman.upload.form');
Route::post('/postman/upload', [PostmanController::class, 'handleUpload'])->name('postman.upload.handle');
Route::get('/postman/view', [PostmanController::class, 'viewContent'])->name('postman.view');
// Route::get('/register/parent', [RegisterController::class, 'showParentForm'])->name('register.parent');
// Route::get('/register/school', [RegisterController::class, 'showSchoolForm'])->name('register.school');
// routes/web.php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

/*
| Email Subscribe/Unsubscribe & Preferences
| - مسارات تحت prefix /email
| - throttle للتحكم في الطلبات
| - one-click محمي بـ signed
*/
Route::prefix('email')->middleware('throttle:20,1')->group(function () {
    // صفحة تأكيد الإلغاء (GET) + تنفيذ الإلغاء (POST)
    Route::get('/unsubscribe',  [UnsubscribeController::class, 'show'])
        ->name('email.unsubscribe.show');
    Route::post('/unsubscribe', [UnsubscribeController::class, 'process'])
        ->name('email.unsubscribe.process');

    // One-click من List-Unsubscribe (رابط موقّع)
    Route::get('/unsubscribe/one-click', [UnsubscribeController::class, 'oneClick'])
        ->name('email.unsubscribe.oneclick')
        ->middleware('signed');

    // صفحة التفضيلات وتحديثها
    Route::get('/preferences',  [UnsubscribeController::class, 'preferencesShow'])
        ->name('email.preferences.show');
    Route::post('/preferences', [UnsubscribeController::class, 'preferencesUpdate'])
        ->name('email.preferences.update');
});

/*
| Route بسيط لاختبار الإرسال
| بيولّد كمان رابط One-Click موقّع في الـ JSON للسهولة
*/
// Route::get('/mail-test', function () {
//     try {
//         Mail::to('mohamedashrafelbayoumi@gmail.com')->send(new TestMail());

//         return response()->json([
//             'ok'         => true,
//             'used_mailer'=> config('mail.default'),
//             'from'       => config('mail.from'),
//             'one_click'  => URL::signedRoute('email.unsubscribe.oneclick', [

//                 'email' => 'mohamedashrafelbayoumi@gmail.com'
//             ]),
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'ok' => false,
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// });
Route::get('/mail-test', function () {
    try {
        // ✅ Send a test email using your default mail configuration
        Mail::to('mohamedashrafelbayoumi@gmail.com')->send(new TestMail());

        // ✅ Return useful debug info (never expose password!)
        return response()->json([
            'ok'      => true,
            'default' => config('mail.default'), // Which mailer is active (smtp, log, etc.)
            'from'    => config('mail.from'),    // Global "from" address + name
            'smtp'    => [                       // Current SMTP configuration
                'host'     => config('mail.mailers.smtp.host'),
                'port'     => config('mail.mailers.smtp.port'),
                'enc'      => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                // ⚠️ Never print the password or sensitive secrets in a response
            ],
        ]);
    } catch (\Throwable $e) {
        // ❌ If SMTP connection fails (blocked port, wrong creds, etc.) it will appear here
        return response()->json([
            'ok'    => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});
