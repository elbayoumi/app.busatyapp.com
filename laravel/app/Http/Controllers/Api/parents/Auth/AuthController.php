<?php

namespace App\Http\Controllers\Api\parents\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Mail\ResetPasswordCode;
use App\Models\My_Parent;
use App\Models\ParentVerfication;
use App\Services\FirebaseTokenService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $firebaseTokenService;

    public function __construct(FirebaseTokenService $firebaseTokenService)
    {
        $this->firebaseTokenService = $firebaseTokenService;
    }
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4|max:60',
                'email' => 'required|email|unique:my__parents|max:100',
                'password' => 'required|min:8|max:40',
                'phone' =>    'nullable|regex:/^[0-9]+$/|min:10|max:20|unique:my__parents',
                'address' => 'nullable|min:5|max:100',
                'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 403);
            }





            $user = My_Parent::create($validator->validated());
            sendCodeVerfication(new ParentVerfication, $user);


            $token =  $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'data' => $user,
                'massage' => __('user created'),
                'token' => $token
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'massage' => $ex->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function resendCode(Request $request)
    {
        $user = $request->user();
        sendCodeVerfication(new ParentVerfication, $user);

        return JSON("success message");
    }
    public function verify(Request $request)
    {
        $user = $request->user();
        // if( 1 == $user->email_verified_at ) {
        //     return response()->json([
        //         'message' => __('Your email is verified'),
        //         'status' => false,
        //     ], 500);
        // }

        $validator = Validator::make($request->all(), [
            'token' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
        }
        $token = $request->token;
        $verifyUser = ParentVerfication::where('user_id', $user->id)->where('code', $token)->first();
        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (1 != $user->email_verified_at) {
                $verifyUser->user->email_verified_at = 1;
                $verifyUser->user->save();

                $this->firebaseTokenService->addToken($user, $request?->firebase_token);
            }
            $this->firebaseTokenService->addToken($user, $request?->firebase_token);
            // myParentFcmLogin

            // $studentService= new StudentService();
            // $studentService->myParentFcmLogin($user ,[$request?->firebase_token]);

            return response()->json([
                'data' => $user->email,
                'massage' => __('Your email is verified'),
            ], 200);
        }

        return response()->json([
            'message' => __('The token is incorrect'),
            'status' => false,
        ], 500);
    }


    public function login(Request $request)
    {
        try {

            $filded =  $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);


            $user = My_Parent::where('email', $filded['email'])->with('subscriptions', function ($q) {
                return $q->where('end_date', '>=', Carbon::now());
            })->first();
            if (!$user  || !Hash::check($filded['password'], $user->password)) {
                return response()->json([
                    'massage' => 'email or password not correct',
                    'status' => false,

                ], 401);
            }

            $token =  $user->createToken('MyApp')->plainTextToken;
            // return 0;
            $this->firebaseTokenService->addToken($user, $request?->firebase_token);
            // myParentFcmLogin

            // $studentService= new StudentService();
            // $studentService->myParentFcmLogin($user ,[$request?->firebase_token]);
            return response()->json([
                'data' => $user,
                'massage' => 'you are login',
                'token' => $token
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'massage' => $ex->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function logout(Request $request)
    {

        $user = $request->user();

        $this->firebaseTokenService->removeToken($user, $request?->input('firebase_token', false));

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return res('', 1, 'تم تسجيل الخروج');
    }
    public function forgot_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:my__parents,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
        }



        $user_last_codes_count = DB::table('parent_password_reset_codes')
            ->where('email', $request->email)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($user_last_codes_count >= 5) {

            return response()->json(['errors' => true, 'messages' => 'لقد تخطيت الحد المسموح به من المحاولات يرجى المحاولة لاحقاً'], 500);
        }

        $code = rand(100000, 999999);
        $password_reset = DB::table('parent_password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => date("Y-m-d H:i:s", strtotime('now'))

        ]);



        $data['code'] = $code;
        $message = "is your reset password code for your account" . " " . $data['code'];

        $mail_data = [
            'recipient' => $request->email,
            'subject' => 'account reset password',
            'body' => $message,
            'code' => $data['code'],
        ];



        if ($password_reset) {
            Mail::to($mail_data['recipient'])->send(new ResetPasswordCode($mail_data));

            return new JsonResponse(['errors' => false, 'message' => 'تم ارسال كود ستعادة كلمة السر'], 200);
        }

        return false;
    }



    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required|email|exists:my__parents,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return res(null, 0, $validator->errors());
        }

        $User = My_Parent::where('email', $request->email)->first();

        if ($User == null) {
            return res(null, 0, 'البريد الالكتروني غير موجود');
        }

        $user_last_code = DB::table('parent_password_reset_codes')
            ->where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();



        if ($user_last_code->code != $request->code) {


            return response()->json(['errors' => true, 'messages' => 'الكود غير صحيح'], 500);
        }

        $new_password = $request->password;
        $User->password = $new_password;
        $User->save();

        $token = $User->createToken('auth_token')->plainTextToken;

        $data = [
            'token' => $token,
        ];
        return res($data, 1, 'تم تغير كلمة السر بنجاح');
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        try {
            // حذف التوكنات من الجدول
            $user->tokens()->delete();

            // حذف الحساب نفسه
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف الحساب بنجاح'
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف الحساب',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
