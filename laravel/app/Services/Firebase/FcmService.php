<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\MessagingException;
use App\Models\FcmToken;
use Exception;
use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as GuzzleHttpClient;

class FcmService
{
    protected $client;
    protected $projectId;
    protected $credentialsFilePath;
    public function __construct()
    {
        try {
            // $this->projectId = "busaty-app";
            $this->credentialsFilePath = Storage::path('json/bussat.json');
            $credentials = json_decode(file_get_contents($this->credentialsFilePath), true);
            $this->projectId = $credentials['project_id'] ?? "busaty-app";

            $this->client = new GoogleClient();
            $this->client->setAuthConfig($this->credentialsFilePath);
            $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $this->client->refreshTokenWithAssertion();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error('Error in FcmService constructor');
        }
    }
    public function subscribeToTopic($topic, $registrationTokens)
    {
        // Retrieve the registration tokens from the request

        try {
            // Initialize Firebase Messaging
            $factory = (new Factory)->withServiceAccount($this->credentialsFilePath);
            $messaging = $factory->createMessaging();

            // Subscribe to the topic
            $response = $messaging->subscribeToTopic($topic, $registrationTokens);

            // Return success response
            return response()->json([
                'message' => 'Successfully subscribed to topic',
                'response' => $response,
            ], 200);
        } catch (MessagingException $e) {
            // Handle error response
            return response()->json([
                'error' => 'Error subscribing to topic: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function sendNotificationToTopic($topic, $title = "Default Title", $body = "Default Body")
    {
        $message = [
            "message" => [
                "topic" => $topic,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "data" => [
                    "priority" => "high",
                    "url" => "https://stage.busatyapp.com/",
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    "message" => $body,
                ],
                "android" => [
                    "ttl" => "60s", // تحديد مدة حياة الإشعار
                    "priority" => "high", // أولوية الإشعار
                ],
                // "content_available" => true, // إضافة هذا المفتاح

            ]
        ];

        $accessToken = $this->getAccessToken();
        $response = $this->sendRequest($accessToken, $message);

        if ($response->failed()) {
            throw new \Exception('FCM Error: ' . $response->body());
        }

        return ['status' => 'Notification sent successfully.'];
    }
    function subscribeTokensToTopics($topics, $registrationTokens)
    {
        // تهيئة Firebase Admin SDK
        $factory = (new Factory)->withServiceAccount($this->credentialsFilePath);
        $messaging = $factory->createMessaging();

        // مصفوفة لتخزين نتائج الاشتراكات
        $results = [];

        // الاشتراك في كل موضوع
        foreach ($topics as $topic) {
            try {
                $response = $messaging->subscribeToTopic($topic, $registrationTokens);
                $results[$topic] = [
                    'success' => true,
                    'response' => $response,
                ];
            } catch (MessagingException $e) {
                $results[$topic] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }


    // الوظيفة الرئيسية لإرسال الإشعارات
    public function sendNotification(array $fcmTokens, $title = "Default Title", $body = "Default Body")
    {
        if (empty($fcmTokens)) {
            throw new \Exception('No FCM tokens provided.');
        }

        $accessToken = $this->getAccessToken();

        foreach ($fcmTokens as $fcmToken) {
            $message = $this->buildMessage($fcmToken, $title, $body);
            $response = $this->sendRequest($accessToken, $message);

            if ($response->failed()) {
                $this->handleErrorResponse($response, $fcmToken);
            }
        }

        return ['status' => 'Notifications sent successfully.'];
    }

    // وظيفة للحصول على الـ Access Token
    protected function getAccessToken()
    {
        $token = $this->client->getAccessToken();
        return $token['access_token'];
    }

    // وظيفة لإنشاء الرسالة
    protected function buildMessage($fcmToken, $title, $body)
    {
        return [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "data" => [
                    "priority" => "high",
                    "urls" => ""
                ]
            ]
        ];
    }

    // وظيفة لإرسال الطلب باستخدام Http
    protected function sendRequest($accessToken, $message)
    {
        $headers = [
            "Authorization" => "Bearer $accessToken",
            'Content-Type' => 'application/json'
        ];

        return Http::withHeaders($headers)
            ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", $message);
    }

    // التعامل مع الاستجابة في حالة وجود خطأ
    protected function handleErrorResponse($response, $fcmToken)
    {
        $responseBody = $response->json();

        if (isset($responseBody['error']['details'][0]['errorCode'])) {
            $errorCode = $responseBody['error']['details'][0]['errorCode'];

            if ($errorCode === 'INVALID_ARGUMENT' || $errorCode === 'UNREGISTERED') {
                $this->removeInvalidToken($fcmToken);
                return; // تجاهل هذا التوكن واستمر
            }
        }

        // إذا حدث خطأ آخر غير التوكن غير صالح
        throw new \Exception('FCM Error: ' . $response->body());
    }

    // وظيفة لإزالة التوكنات غير الصالحة
    protected function removeInvalidToken($token)
    {
        // احذف التوكن غير الصالح من قاعدة البيانات أو التخزين الخاص بك
        // مثال:
        FcmToken::where('fcm_token', $token)->delete();
    }
    public function unsubscribeFromTopic($topic, $registrationTokens)
    {
        try {
            // Initialize Firebase Messaging
            $factory = (new Factory)->withServiceAccount($this->credentialsFilePath);
            $messaging = $factory->createMessaging();

            // Unsubscribe from the topic
            $response = $messaging->unsubscribeFromTopic($topic, $registrationTokens);

            // Return success response
            return response()->json([
                'message' => 'Successfully unsubscribed from topic',
                'response' => $response,
            ], 200);
        } catch (MessagingException $e) {
            // Handle error response
            return response()->json([
                'error' => 'Error unsubscribing from topic: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function sendBatchNotifications(array $fcmTokens, $title, $body)
    {
        $accessToken = $this->getAccessToken();
        $chunks = array_chunk($fcmTokens, 500); // Firebase supports up to 500 tokens per request.

        foreach ($chunks as $chunk) {
            $message = [
                "message" => [
                    "notification" => ["title" => $title, "body" => $body],
                    "tokens" => $chunk,
                    "android" => ["priority" => "high"],
                    "apns" => ["headers" => ["apns-priority" => "10"]],
                ]
            ];

            $response = $this->sendRequest($accessToken, $message);

            if ($response->failed()) {
                Log::error('Batch Notification Error', ['response' => $response->json()]);
            }
        }
    }

    function notifyByFirebase($title, $body, $tokens) // paramete 5 =>>>> $type
    {
        foreach ($tokens as $token) {
            $url = 'https://fcm.googleapis.com/v1/projects/busaty-app/messages:send';
            $serviceAccountPath =  $this->credentialsFilePath;
            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccountPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
            $data = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    // Android-specific payload
                    "android" => [
                        "priority" => "high",
                        "notification" => [
                            "icon" => "",
                            "color" => "#FF0000",
                            "click_action" => "OPEN_ACTIVITY",
                        ]
                    ],
                    // iOS-specific payload
                    "apns" => [
                        "headers" => [
                            "apns-priority" => "10"
                        ],
                        "payload" => [
                            "aps" => [
                                "alert" => [
                                    "title" => $title,
                                    "body" => $body
                                ],
                                "badge" => 1,
                                "sound" => "default",
                                "category" => "NEW_MESSAGE_CATEGORY",
                            ]
                        ]
                    ],
                    "webpush" => [
                        "headers" => [
                            "Urgency" => "high"
                        ],
                        "notification" => [
                            "icon" => "",
                            "click_action" => ""
                        ]
                    ]
                ]
            ];

            $httpClient = new GuzzleHttpClient();
            try {
                $response = $httpClient->post($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $data,
                    'verify' => true,
                ]);

                return $response->getBody()->getContents();
            } catch (\Exception $e) {
                // Log the error
                Log::error('Failed to send notification', ['error' => $e->getMessage()]);
                return $e->getMessage();
            }
        }
    }
}
