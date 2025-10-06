<?php

namespace App\Http\Controllers;


use App\Services\Firebase\NewFcmService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
  public function test()
  {
    return "Test";
  }

  public function updateMigration()
  {
    // there are previous codes in the git history

    // before any thing run php artisan migrate

    // *********** start of  06/05/2025
    // drop not using tables  
    // Schema::dropIfExists('notification_schools');
    // Schema::dropIfExists('notification_parants');
    // Schema::dropIfExists('notification_attendants');

    // Trip::where('status', 1)
    //   ->lazy()->each(function ($trip) {
    //   // update end_at
    //   $trip->end_at = $trip->created_at->addHour();
    //   $trip->save();
    // });
    // *********** end of  06/05/2025  ( done on ( ali local -- test server  ) )

    return "Done Successfully";
  }

  public function postmanCollectionUpdatesInfo()
  {
    return response()->json([
      "" => "",
      "12/05/2025" => [
        "schools/temporary-address/[change-bus]",
    ],
      "11/05/2025" => [
        "postman collection updates info" , 
        "schools/temporary-address/[respond-to-the-request, get-status, index]",
        "parents/temporary-address/[get-status, cancel-processing-request, store, update, index]",
    ],
      '06/05/2025' => ['shared/social-auth/new-password-social-login'],
      '05/05/2025' => ['schools/profile-and-settings/notification settings'],
      '04/05/2025' => ['schools/trips/previous trips', 'schools/trips/general-trip-absents', 'schools/trips/general-trip-attendants'],
    ]);
  }

  public function sendTestNotification(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'device_token' => 'required|string',
      ]);

      if ($validator->fails()) {
        return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
      }

      $validated = $validator->validated();

      $fcmService = new NewFcmService();
      $fcmService->sendToToken($validated['device_token'], 'test title', 'test body');

      return "Done Successfully";
    } catch (\Exception $e) {
      return response()->json(['errors' => true, 'messages' => $e->getMessage()], 500);
    }
  }
}
