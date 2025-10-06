<?php

namespace App\Http\Controllers\Api\schools\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Mail\ResetPasswordCode;
use App\Models\School_verfication;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Models\SchoolFirebaseToken;
use App\Services\FirebaseTokenService;
use App\Traits\Schools\CreateTrait;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $firebaseTokenService;

    public function __construct(FirebaseTokenService $firebaseTokenService)
    {
        $this->firebaseTokenService = $firebaseTokenService;
    }
    use CreateTrait;
    /**
     * Register a new school
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $lang_default_classrooms
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, $lang_default_classrooms = 'default_classrooms')
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4|max:60',
                'email' => 'required|email|unique:schools|max:100',
                'password' => 'required|min:8|max:40',
                'phone' => 'required|regex:/^[0-9]+$/|min:10|max:20|unique:schools',
                'address' => 'nullable|min:5|max:50',
                'latitude' => 'required|between:-90,90',
                'longitude' => 'required|between:-180,180'
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 400);
            }

            // Get the validated data
            $validatedData = $validator->validated();

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Create a new school record
                $user = School::create($validatedData);
                // Create an authentication token for the user
                $token = $user->createToken('MyApp')->plainTextToken;
                $this->firebaseTokenService->addToken($user, $request?->firebase_token);
                sendCodeVerfication(new School_verfication, $user);
                DB::commit();
                // Return a successful response
                return response()->json([
                    'data' => $user,
                    'message' => __("Data has been added successfully"),
                    'token' => $token,
                    'status' => true,
                ], 201);
            } catch (\Exception $ex) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                return JSONerror($ex->getMessage(), 500);
            }
        } catch (\Exception $ex) {
            // Handle any other exceptions
            return JSONerror($ex->getMessage(), 500);
        }
    }

    /**
     * Verify the email of the school
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $user =  $request->user();

        if (1 == $user->email_verified_at) {
            return response()->json([
                'message' => __("Already verified."),
                'status' => false,
            ], 500);
        }

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }
        $token = $request->token;
        $verifyCode = School_verfication::where('user_id', $user->id)->where('code', $token)->first();


        if (!is_null($verifyCode)) {
            if (Carbon::now() > $verifyCode->updated_at->addHour()) {
                return response()->json([
                    'message' => __("An Expired code."),
                    'status' => false,
                ], 422);
            }

            $user = $verifyCode->user;

            if (1 != $user->email_verified_at) {
                $verifyCode->user->email_verified_at = 1;
                $verifyCode->user->save();
                $this->firebaseTokenService->addToken($user, $request?->firebase_token);

                return JSON($user);
            }
        }

        return response()->json([
            'message' => __("Something was wrong"),
            'status' => false,
        ], 500);
    }
    /**
     * Login a school
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:schools,email',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }

            $data = $request->all();


            $user = School::where('email', $data['email'])->with('subscriptions', function ($q) {
                return $q->where('end_date', '>=', Carbon::now());
            })->first();

            if (!$user  || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'massage' => __("Email not found"),
                    'status' => false,

                ], 401);
            }
            $token =  $user->createToken('MyApp')->plainTextToken;
            $this->firebaseTokenService->addToken($user, $request?->firebase_token);

            return response()->json([
                'data' => $user,
                'massage' => __("you are login"),
                'token' => $token
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'massage' => $ex->getMessage(),
                'status' => false,
            ], 401);
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
            'email' => 'required|email|exists:schools,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }



        $user_last_codes_count = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($user_last_codes_count >= 5) {

            return response()->json(['errors' => true, 'messages' => 'لقد تخطيت الحد المسموح به من المحاولات يرجى المحاولة لاحقاً'], 500);
        }

        $code = rand(100000, 999999);
        $password_reset = DB::table('password_reset_codes')->insert([
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



    public function resendCode(Request $request)
    {
        $user = $request->user();
        sendCodeVerfication(new School_verfication, $user);

        return JSON("success message");
    }
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required|email|exists:schools,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return res(null, 0, $validator->errors());
        }

        $User = School::where('email', $request->email)->first();

        if ($User == null) {
            return res(null, 0, 'البريد الالكتروني غير موجود');
        }

        $user_last_code = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();



        if ($user_last_code->code != $request->code) {


            return response()->json(['errors' => true, 'messages' => __("Something was wrong")], 500);
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

    public function notfound() {}
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        try {
            // حذف جميع التوكنات
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
    // private function processCreateDefaultClassRooms($school, $lang_default_classrooms)
    // {
    //     $this->getStoreFixedTrip($school);
    //     $grades = Grade::orderBy('order', 'desc')->get();

    //     // $grades = Grade::all();
    //     $bus = $this->getStorebus($school);
    //     foreach ($grades as $grade) {
    //         SchoolGrade::create([
    //             'grade_id' => $grade->id,
    //             'school_id' => $school->id,
    //         ]);
    //         $classrooms = json_decode($grade->{$lang_default_classrooms}, true);
    //         if (is_array($classrooms)) {
    //             foreach (array_reverse($classrooms) as $grd) {
    //                 $classroom = Classroom::create([
    //                     'name' => $grd,
    //                     'grade_id' => $grade->id,
    //                     'school_id' => $school->id,
    //                 ]);
    //             }
    //         }
    //     }

    //     $this->storeStudents($school, $bus->id, $classroom->id, $grades[0]->id, 'student ' . json_decode($grades[0]->default_classrooms)[0]);
    // }





}
