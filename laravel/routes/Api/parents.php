<?php

use App\Http\Controllers\Api\ErrorController;
use Illuminate\{
    Http\Request,
    Support\Facades\Route
};
use Carbon\Carbon;
use App\Models\My_Parent;

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\parents\{
    TripsController,
    schoolsMessages\SchoolsMessagesController,
    Children\ChildrenController,
    Children\MapController,
    addresses\AddressesController,
    attendantsMessages\AttendantsMessagesController,
    absences\AbsencesController,
    Auth\AuthController,
    UserController,
    AdesController,
};
use App\Http\Controllers\Api\parents\addresses\TemporaryAddressController;

// ----------- Public Auth Routes -----------

// Register a new parent account
Route::post('register', [AuthController::class, 'register']);

// Delete parent account (requires authentication)
Route::delete('delete', [AuthController::class, 'deleteAccount']);

// Login parent account
Route::post('login', [AuthController::class, 'login']);

// ----------- Protected Routes (Require Auth & Parent Role) -----------
Route::middleware(['auth:parent', 'is_parents'])->group(function () {

    // Logout parent account
    Route::post('/logout', [AuthController::class, 'logout']);

    // Get current parent user info with active subscriptions
    Route::get('/user', function (Request $request) {
        return My_Parent::whereId($request->user()->id)->with('subscriptions', function ($q) {
            return $q->where('end_date', '>=', Carbon::now());
        })->first();
    });

    // Email verification for parent
    Route::post('/verify', [AuthController::class, 'verify']);

    // Resend verification code
    Route::post('/resendCode', [AuthController::class, 'resendCode']);

    // ----------- Verified Parent Only -----------
    Route::middleware(['is_verify'])->group(function () {

        // Update parent data
        Route::post('data/update', [UserController::class, 'data_update']);

        // Complete parent profile data
        Route::post('data/complete', [UserController::class, 'complete']);

        // Update parent password
        Route::post('password/update', [UserController::class, 'password_update']);

        // ----------- Children Management -----------
        Route::prefix('childrens')->group(function () {

            // Get all children for parent
            Route::get('all', [ChildrenController::class, 'all']);

            // Show specific child info
            Route::get('show/{id}', [ChildrenController::class, 'show']);

            // Add new child
            Route::post('store', [ChildrenController::class, 'store']);

            // Upload child image
            Route::post('upload/image', [ChildrenController::class, 'uploadImage']);

            // Delete child
            Route::delete('destroy/{id}', [ChildrenController::class, 'destroy']);

            // Search for children
            Route::get('search', [ChildrenController::class, 'search']);

            // Show child map location
            Route::get('map/show/{id}', [MapController::class, 'show']);
        });

        // ----------- Absences Management -----------
        Route::prefix('absences')->group(function () {

            // List all absences
            Route::get('index', [AbsencesController::class, 'index']);

            // Show specific absence
            Route::get('show/{id}', [AbsencesController::class, 'show']);

            // Get absence status for a student
            Route::get('status/{student_id}/{trip_type?}', [AbsencesController::class, 'status']);

            // Store new absence for a student
            Route::post('store/{student_id}', [AbsencesController::class, 'store']);

            // Delete absence
            Route::delete('destroy/{id}', [AbsencesController::class, 'delete_absences']);

            // Create absence (form)
            Route::get('create', [AbsencesController::class, 'create']);
        });

        // ----------- School Messages -----------
        Route::prefix('schools/messages')->group(function () {

            // Get all messages from schools
            Route::get('all', [SchoolsMessagesController::class, 'getAll']);

            // Show specific school message
            Route::get('show/{id}', [SchoolsMessagesController::class, 'getShow']);

            // Get new messages from schools
            Route::get('new', [SchoolsMessagesController::class, 'getNew']);

            // Mark all school messages as read
            Route::post('all/read', [SchoolsMessagesController::class, 'getAllRed']);
        });

        // ----------- Attendants Messages -----------
        Route::prefix('attendants/messages')->group(function () {

            // Get all messages from attendants
            Route::get('all', [AttendantsMessagesController::class, 'getAll']);

            // Show specific attendant message
            Route::get('show/{id}', [AttendantsMessagesController::class, 'getShow']);

            // Get new messages from attendants
            Route::get('new', [AttendantsMessagesController::class, 'getNew']);
        });

        // ----------- Addresses Management -----------
        Route::prefix('addresses/order')->group(function () {
            // List all addresses
            Route::get('index', [AddressesController::class, 'index']);

            // Create new address (form)
            Route::get('create', [AddressesController::class, 'create_address']);

            // Store new address
            Route::post('store', [AddressesController::class, 'getStore']);

            // Show specific address
            Route::get('show/{address_id}', [AddressesController::class, 'show']);

            // Get current address status for a student
            Route::get('status/{student_id}', [AddressesController::class, 'current']);

            // Cancel address for a student
            Route::delete('cancel/{student_id}', [AddressesController::class, 'cancel']);
        });

        // ----------- Temporary Addresses -----------
        Route::prefix('temporary-addresses')->group(function () {
            // Get temporary address status
            Route::get('status', [TemporaryAddressController::class, 'status']);

            // Cancel temporary address
            Route::patch('cancel/{temporaryAddress:id}', [TemporaryAddressController::class, 'cancel']);

            // Resource routes: index, store, update for temporary addresses
            Route::resource('',  TemporaryAddressController::class )
                ->only([ 'index', 'store', 'update'])
                ->parameter('', 'temporaryAddress');
        });

        // ----------- Notifications -----------
        // Notifications resource routes (index, show, store, update, destroy)
        Route::apiResource('notifications', NotificationController::class , ['as' => 'parents']);

        // Mark all notifications as read
        Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);

        // ----------- Ades (Unknown context) -----------
        Route::get('ades', [AdesController::class, 'index']);

        // ----------- Trips Management -----------
        Route::apiResource('trips',TripsController::class , ['as' => 'parents'] );
    });
});

// ----------- Password Reset Routes -----------

// Send forgot password code to parent email
Route::post('forgot-password', [AuthController::class, 'forgot_password']);

// Reset parent password using code
Route::post('/reset-password', [AuthController::class, 'reset_password']);
