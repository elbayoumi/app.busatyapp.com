<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Support\Facades\Http;
use App\Services\FirebaseTokenService;
use Illuminate\Support\Facades\Validator;
use App\Services\Firebase\FirebaseAuthService;

class FirebaseAuthController extends Controller
{
    protected FirebaseAuthService $firebase;
    protected $firebaseTokenService;

    public function __construct(FirebaseAuthService $firebase, FirebaseTokenService $firebaseTokenService)
    {
        $this->firebase = $firebase;
        $this->firebaseTokenService = $firebaseTokenService;
    }

    public function login(Request $request, $table)
    {
        $request->validate([
            'accessToken' => ['required', 'string', 'min:50'],
        ]);


        // Use the first available token
        $accessToken = $request->accessToken;

        Log::info('User login attempt via Google', [
            'accessToken_preview' => substr($accessToken, 0, 8) . '...'
        ]);

        if (!$accessToken) {
            return response()->json([
                'message' => 'No access token provided'
            ], 400);
        }

        try {
            // Get user info from Google API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => $response->json(),
                ], 401);
            }

            // Extract user data from Google response
            $googleUser = $response->json();
            $email = $googleUser['email'] ?? null;
            $name = $googleUser['name'] ?? 'Unknown';
            $uid = $googleUser['sub'] ?? null;
            $avatar = $googleUser['picture'] ?? null;

            if (!$email || !$uid) {
                return response()->json(['message' => 'Invalid user data from Google'], 422);
            }

            // Determine the appropriate user model class
            $modelClass = $this->getModelFromTable($table);
            if (!$modelClass || !class_exists($modelClass)) {
                return response()->json(['message' => 'Invalid table name'], 400);
            }

            // Check if user already exists
            $user = $modelClass::where('email', $email)->first();

            if (!$user) {
                // Create new user if not exists
                $user = $modelClass::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt(Str::random(16))
                ]);
            }
            $user->email_verified_at = 1;
            $user->save();

            // making the phone in school (nullable) by editing test domain database

            // Create access token for the user
            $token = $user->createToken('MyApp')->plainTextToken;

            // Save Firebase token if provided
            if ($request->filled('firebase_token')) {
                $this->firebaseTokenService->addToken($user, $request->firebase_token);
            }

            // Save or update user's social account
            $user->socialAccounts()->updateOrCreate(
                ['provider' => 'google', 'provider_id' => $uid],
                [
                    'email' => $email,
                    'avatar' => $avatar,
                    'token' => $accessToken,
                ]
            );
            return response()->json([
                'data' => $user,
                'massage' => __('you are login'),
                'token' => $token
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Google Login Exception', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * add enw password to the user logined by social media
     */
    public function newPassword(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'password' => ['required', 'string', 'min:8' , 'confirmed'],
      ]);

      if ($validator->fails()) {
        return response()->json( [ 'errors' => true, 'messages' =>  $validator->errors()], 422);
      }

      $request->user()->update([
        'password' => $request->password
      ]);

      return res(null, 1, __("Password changed successfully"));
    }
    /**
     * Return the model class name based on the table type.
     */
    protected function getModelFromTable(string $table): ?string
    {
        return match ($table) {
            'schools' => \App\Models\School::class,
            'parents' => \App\Models\My_Parent::class,
            'attendants' => \App\Models\Attendant::class,
            default => null,
        };
    }
    function firebaseLoginStatus(Request $request, $app)
    {
        try {
            $app = App::where('name', $app)->first();
            return JSON($app->google_auth);
        } catch (\Throwable $th) {
            Log::error('Firebase Login Status Error', [
                'message' => $th->getMessage()
            ]);
            return JSONerror('something went wrong', 500);
        }
    }
}
