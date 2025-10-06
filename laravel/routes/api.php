<?php

use Illuminate\{
    Support\Facades\Route
};

use App\Http\Controllers\Api\{
    AppController,
    CouponController,
    ErrorController,
    FirebaseAuthController,
    QuestionController,
    SubscriptionController,
};
use App\Http\Controllers\Api\Callback\TrackingController;
use App\Http\Controllers\Verification;
use Illuminate\Support\Facades\Log;






use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\My_Parent;
use App\Models\Attendant;
use App\Models\School;


Route::get('400', [ErrorController::class, 'badRequest'])->name('400');
Route::get('401', [ErrorController::class, 'unauthorized'])->name('401');
Route::get('403', [ErrorController::class, 'forbidden'])->name('403');
Route::get('404', [ErrorController::class, 'notFound'])->name('404');
Route::get('405', [ErrorController::class, 'methodNotAllowed'])->name('405');
Route::get('409', [ErrorController::class, 'conflict'])->name('409');
Route::get('422', [ErrorController::class, 'unprocessableEntity'])->name('422');
Route::get('500', [ErrorController::class, 'serverError'])->name('500');
// Parent Register
Route::post('/parent/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:my__parents,email',
        'password' => 'required|string|min:6',
    ]);

    $user = My_Parent::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
    ]);

    return response()->json([
        'message' => 'Registration successful',
        'user' => $user,
    ], 201);
});

// Parent Login
// Parent Login
Route::post('/parent/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = App\Models\My_Parent::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('parent-token')->plainTextToken,
        'user' => $user,
    ]);
});


// Attendant Login
Route::post('/attendant/login', function (Request $request) {
    $user = Attendant::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    return response()->json([
        'token' => $user->createToken('attendant-token')->plainTextToken,
        'user' => $user,
    ]);
});

// School Login
Route::post('/school/login', function (Request $request) {
    $user = School::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    return response()->json([
        'token' => $user->createToken('school-token')->plainTextToken,
        'user' => $user,
    ]);
});

Route::middleware('auth:parent')->get('/parent/me', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:attendant')->get('/attendant/me', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:school')->get('/school/me', function (Request $request) {
    return $request->user();
});





















// Route::get('{type}/question',[QuestionController::class,'all'])->where('type', 'parents|schools|attendants');
// 'auth:sanctum'
Route::get('/postman-collection-updates-info', [\App\Http\Controllers\TestController::class, 'postmanCollectionUpdatesInfo']);
// Route::post('/send-test-notification', [\App\Http\Controllers\TestController::class , 'sendTestNotification'])
//   ->middleware(['auth:sanctum']);

Route::group(['prefix' => '{type}', 'where' => ['type' => 'parents|schools|attendants']], function () {
    Route::get('question', [QuestionController::class, 'all'])->name('questions.all');
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('new-password', [FirebaseAuthController::class, 'newPassword']);

        Route::prefix('subscriptions')->group(function () {
            Route::post('store', [SubscriptionController::class, 'store']);
            Route::get('index', [SubscriptionController::class, 'index']);
            Route::get('create/{id}', [SubscriptionController::class, 'create']);
            Route::post('update/{id}', [SubscriptionController::class, 'update']);
            Route::post('coupon/{code}', [CouponController::class, 'check']);
            Route::post('status', [SubscriptionController::class, 'status']);
        });
    });
    Route::post('/auth/firebase-login', [FirebaseAuthController::class, 'login']);
    Route::get('/auth/firebase-login-status', [FirebaseAuthController::class, 'firebaseLoginStatus']);

    Route::get('/app/updating', [AppController::class, 'updating']);
});



Route::get('/app/status', [AppController::class, 'index']);

Route::apiResource('question', QuestionController::class);
// Route::post('/track/batch', [TrackingController::class, 'storeBatch'])->middleware('cors.tracking');
// Group all API routes with 'api' middleware
Route::middleware('api')->group(function () {



    // Special case: /track/batch should NOT have 'api' middleware
    Route::post('/track/batch', [TrackingController::class, 'storeBatch'])
        ->withoutMiddleware('api') // Remove 'api' middleware for this route only
        ->middleware('cors.tracking'); // Apply custom CORS middleware for tracking
});

Route::get('user/activate/{id}/{model_name}', [Verification::class, 'activate'])->name('user.activate');
Route::get('/lat/{lat}', function ($lat) {
    Log::info('lat : ' . $lat . ' .');

    return JSON('success');
});
// routes/web.php
use App\Http\Controllers\UnsubscribeController;

Route::match(['GET','POST'], '/unsubscribe/one-click', [UnsubscribeController::class, 'oneClick'])
    ->name('unsubscribe.oneclick');
