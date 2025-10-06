<?php

namespace App\Http\Controllers;
// use App\Enum\TripStatusEnum;
// use App\Enum\TripTypebBusEnum;
// use App\Enum\TripTypeEnum;

use App\Models\FcmToken;
use App\Models\Trip;
use App\Notifications\StudentNotification;
use App\Notifications\TripNotification;
use App\Services\Firebase\FcmService;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    protected $fcmService;
    function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    function fcm(Request $request)
    {
        $startTime = microtime(true);

        try {
            $trip = Trip::with('bus.students')->latest()->first();
                $students = $trip->bus->students;

                if ($students->isNotEmpty()) {
                    $title = 'صباح الخير';
                    $body = 'بداية الرحلة بتاريخ ' . $trip->trips_date; // تأكد من أن هذا نص



                } else {
                    return response()->json(['message' => 'لا يوجد طلاب لإرسال الإشعار إليهم.'], 404);
                }


            // إرسال الإشعار باستخدام التوكنات
//             $response = $this->fcmService->sendNotification([
//                 "cU7adEEXTu6mpR4HSdGI-_:APA91bHh99APvI8mq-CgaadOwKdcpfafAJUkcwxlg2e8q2H7XOBlZksc6uadbh9qACwrtIbrh8dpzooRC-DAlqmc5nzxokcdVW1lFsghcw7C_X6WQZtQ97j0IW6BlTXVWf8ZACjOWBrY"
// ,"en0MLc9fRvmQx57GfQrIS7:APA91bE26Vrpb9oBDZ5tplNPNQWo4aW9k1nXSEBObWG0BkyL0i8tRojJ2zMXk4iROt1QL5UaUPA_zPm8R9xf00QVlkE8H2HE8hnz9jRXZlmWDsVM1feHK9diC6_OiYbE1pu-5AoBEvjz",
//                 "cZ7svRwQQgKysPOUwXGmbW:APA91bG4s4sCFWa8WhxCDUcjPb89DI21EcHMuQXeyizXVFUxMzf5e-DStvHqyI_ar5QggZHYmWZvqllPUknAT1Nxm5V--CYF1ym1ibM8hyNQ73s9O7lYWpdVIghLSM-KbHf1PHn4xEMc"
//                 ,"cIaC7p74T0K5f6i5eQxvwA:APA91bG7XLTqdgG8w1Y-Q5aBfmv2r_fmA3fAuVVYPsfB3wpjMc6lGuyt1HlaCrT7npJfJabYkcGvWayct7rQ1BGoN829mBLkBFPkAbqcqrh4MjBRtdFNR3eK03XG0Ip0R0nrAjTl0Ora",
//                 "jkhuiguyfvgytf"
//             ]
//         );
            // طباعة الاستجابة للتحقق
            // return response()->json(Notification::send($trip, new TripNotification('صباح الخير', $body)));
            $fcmTokens = FcmToken:: pluck('fcm_token')->toArray();
            // return $fcmTokens;

            // (new FcmService)->subscribeToTopic('string5Topic',$fcmTokens);
                (new FcmService)->notifyByFirebase( $title , $body,$fcmTokens );
                $endTime = microtime(true);

                // Calculate the duration in seconds
                $executionTime = $endTime - $startTime;
return $executionTime;
        } catch (\Exception $e) {
            // طباعة أي خطأ يحدث
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'Device token updated successfully']);
    }

    /**
     * Send a Firebase Cloud Message (FCM) to a user using their device token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFcmNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $fcm = $user->fcm_token;

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $request->title;
        $description = $request->body;
        $projectId = config('services.fcm.project_id'); # INSERT COPIED PROJECT ID

        $credentialsFilePath = Storage::path('app/json/file.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}
