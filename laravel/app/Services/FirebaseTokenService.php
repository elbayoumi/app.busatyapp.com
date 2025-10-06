<?php

namespace App\Services;

use App\Services\Firebase\FcmService;
use Illuminate\Support\Facades\Log;
use Mpdf\Tag\Q;

class FirebaseTokenService
{
    protected FcmService $fcmService;

    public function __construct()
    {
        // Initialize the student repository and FCM service
        $this->fcmService = new FcmService;
    }

    public function addToken(object $user,  $token): void
    {
        try {

            if ($token) {
                // Get existing FCM tokens
                $fcmTokens = $user->fcmTokens->pluck('fcm_token')->toArray();

                // Check if the token already exists
                if (!in_array($token, $fcmTokens)) {
                    $user->fcmTokens()->create(['fcm_token' => $token]);
                    $topics = $user->topics()->pluck('name')->toArray();
                    $this->fcmService->subscribeTokensToTopics($topics, [$token]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error adding fcm token to user');
        }

    }
    public function removeToken(object $user,  $token, array $topics = null): void
    {
        if ($token) {
            // If topics are not passed, get the user's current subscribed topics
            // $topics = $topics ?? $user->topics->pluck('name');

            // // Ensure the topics list is not empty
            // if ($topics->isNotEmpty()) {
            //     // Unsubscribe from all topics
            //     foreach ($topics as $topic) {
            //         try {
            //             $this->fcmService->unsubscribeFromTopic($topic, $token);
            //         } catch (\Exception $e) {
            //             // Handle any exceptions that occur during unsubscription
            //             \Log::error('Error unsubscribing from topic: ' . $e->getMessage());
            //         }
            //     }
            // }

            // Delete the token from the database
            $deleted = $user->fcmTokens()->where('fcm_token', $token)->delete();

            if (!$deleted) {
                Log::warning('Failed to delete FCM token: ' . $token);
            }
        }
    }

}
