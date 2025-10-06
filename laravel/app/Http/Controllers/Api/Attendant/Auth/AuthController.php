<?php

namespace App\Http\Controllers\Api\Attendant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Attendant;
use App\Services\FirebaseTokenService;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    protected $firebaseTokenService;

    public function __construct(FirebaseTokenService $firebaseTokenService)
    {
        $this->firebaseTokenService = $firebaseTokenService;
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {


            $filded =  $request->validate([
                'username' => 'required|string|min:3|max:40',
                'password' => 'required|min:6|max:40',
            ]);


            $user = Attendant::where('username', $filded['username'])->first();

            if (!$user  || !Hash::check($filded['password'], $user->password)) {
                return response()->json([
                    'massage' => 'username or password not correct',
                    'status' => false,

                ], 401);
            }
            $token =  $user->createToken('MyApp')->plainTextToken;
            // firebase_token($user, $request);
            $this->firebaseTokenService->addToken($user, $request?->firebase_token);

            return response()->json([
                'data' => $user,
                'massage' => __('you are login'),
                'token' => $token
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'massage' => $ex->getMessage(),
                'status' => false,
            ], 404);
        }
    }

    /**
     * Remove the token of the currently logged in user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user=$request->user();

        $this->firebaseTokenService->removeToken($user, $request?->input('firebase_token', false));
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return res('', 1, 'تم تسجيل الخروج');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return res(null, 0, $validator->errors());
        }

        $User = Attendant::where('email', $request->email)->first();

        if ($User == null) {
            return res(null, 0, 'البريد الالكتروني غير موجود');
        }

        $user_last_codes_count = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($user_last_codes_count >= 5) {
            return res('', 2, 'لقد تخطيت الحد المسموح به من المحاولات يرجى المحاولة لاحقاً');
        }

        $code = rand(100000, 999999);
        DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => date("Y-m-d H:i:s", strtotime('now'))

        ]);

        // Mail::to($request->user())->send(new ResetPasswordCode($code));
        return res(null, 1, 'تم ارسال كود ستعادة كلمة السر');
    }
    /**
     * Reset password for the given user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return res(null, 0, $validator->errors());
        }

        $User = Attendant::where('email', $request->email)->first();

        if ($User == null) {
            return res(null, 0, __('Email not found'));
        }

        $user_last_code = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();



        if ($user_last_code->code != $request->code) {
            if ($user_last_code->try >= 3) {
                return res('', 2,  __('You have exceeded the allowed number of attempts'));
            }

            $user_last_code = DB::table('password_reset_codes')
                ->where('id', $user_last_code->id)
                ->update(['try' => $user_last_code->try + 1]);
            return res('', 0, __("The code is incorrect"));
        }





        $new_password = Hash::make($request->password);
        $User->password = $new_password;
        $User->save();

        $token = $User->createToken('auth_token')->plainTextToken;

        $data = [
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];
        return res($data, 1, __('Password has been changed successfully'));
    }
}
