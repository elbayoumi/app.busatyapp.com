<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{
    NotificationAttendant,
    NotificationParant,
    Student,
    Attendant,
    My__parent_student,
    My_Parent,
    NotificationSchool,
    ParentVerfication,
    School,
    School_verfication,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login_page()
    {
        // dd(Hash::make('atboo797@gmail.com'))  ;

        //         $students = Student::where('school_id', 1)->get();

        // $allFirebaseTokens = [];

        // foreach ($students as $student) {
        //     $firebaseTokens = $student->my_Parents->pluck('firebase_token')->toArray();
        //     $allFirebaseTokens = array_merge($allFirebaseTokens, $firebaseTokens);
        // }

        // // Now, $allFirebaseTokens contains an array of all 'firebase_token' values for parents associated with students
        // dd($allFirebaseTokens);

        // dd(Hash::make('Cdb@$5Fjy'));
        return view('dashboard.auth.login');
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);


        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->route('dashboard.home')->withSuccess('مرحباً ' . Auth::guard('web')->user()->name);;
        }

        return back()->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Session::flush();
        Session::regenerate();
        return redirect('dashboard/login');
    }

    public function forgot_password_page()
    {
        return view('dashboard.auth.passwords.email');
    }

    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('staff')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ? back()->with(['status' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function reset_password_token($token)
    {
        return view('dashboard.auth.passwords.reset', ['token' => $token]);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('staff')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('dashboard.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }


    public function registerSchool()
    {
        return view('dashboard.auth.register', ['type' => 'school']);
    }
    public function showParentRegisterForm()
    {
        return view('dashboard.auth.register')->with('type', 'parent');
    }

    public function registerParent(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:60',
            'email' => 'required|email|unique:my__parents,email|max:100',
            'password' => 'required|min:8|max:40|confirmed',
            'phone' => 'nullable|regex:/^[0-9]+$/|min:10|max:20|unique:my__parents,phone',
            'address' => 'nullable|min:5|max:100',
        ]);

        $parent = new My_Parent();
        $parent->name = $request->name;
        $parent->email = $request->email;
        $parent->password = Hash::make($request->password);
        $parent->phone = $request->phone;
        $parent->address = $request->address;
        $parent->save();

        // Send code
        sendCodeVerfication(new ParentVerfication, $parent);

        return redirect()->route('parent.verify')->with('success', 'Account created! Please verify your email.');
    }

    public function showParentVerifyForm()
    {
        return view('dashboard.auth.verify');
    }

    public function verifyParent(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $parent = auth()->user(); // or get from session

        $verify = ParentVerfication::where('user_id', $parent->id)
            ->where('code', $request->token)
            ->first();

        if ($verify) {
            $parent->email_verified_at = now();
            $parent->save();
            return redirect()->route('dashboard.login')->with('success', 'Email verified!');
        }

        return back()->withErrors(['token' => 'The token is incorrect']);
    }

    public function showRegisterForm()
    {
        return view('dashboard.auth.register', ['type' => 'school']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:60',
            'email' => 'required|email|unique:schools,email|max:100',
            'password' => 'required|min:8|max:40|confirmed',
            'phone' => 'required|regex:/^[0-9]+$/|min:10|max:20|unique:schools,phone',
            'address' => 'nullable|min:5|max:50',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180'
        ]);

        DB::beginTransaction();
        try {
            $school = new School();
            $school->name = $request->name;
            $school->email = $request->email;
            $school->password = Hash::make($request->password);
            $school->phone = $request->phone;
            $school->address = $request->address;
            $school->latitude = $request->latitude;
            $school->longitude = $request->longitude;
            $school->save();

            sendCodeVerfication(new School_verfication(), $school);

            DB::commit();
            return redirect()->route('school.verify.form')->with('success', 'Registered! Please verify your email.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    public function showVerifyForm()
    {
        return view('dashboard.schools.auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['token' => 'required']);
        $school = auth()->user(); // أو حسب الجلسة

        $verifyCode = School_verfication::where('user_id', $school->id)->where('code', $request->token)->first();

        if ($verifyCode && Carbon::now()->lt($verifyCode->updated_at->addHour())) {
            $school->email_verified_at = now();
            $school->save();
            return redirect()->route('school.login.form')->with('success', 'Email verified successfully.');
        }

        return back()->withErrors(['token' => 'Invalid or expired token.']);
    }

    public function showLoginForm()
    {
        return view('dashboard.schools.auth.login');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:schools,email',
            'password' => 'required|min:8',
        ]);

        $school = School::where('email', $request->email)->first();

        if (!$school || !Hash::check($request->password, $school->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        auth()->login($school);
        return redirect()->route('dashboard.home')->with('success', 'Welcome!');
    }

    public function logoutUser(Request $request)
    {
        auth()->logout();
        return redirect()->route('school.login.form')->with('success', 'Logged out.');
    }

    public function showForgotForm()
    {
        return view('dashboard.schools.auth.forgot');
    }

    public function forgot_password_user(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:schools,email']);

        $count = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($count >= 5) {
            return back()->withErrors(['email' => 'Too many attempts. Please try again later.']);
        }

        $code = rand(100000, 999999);
        DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => now()
        ]);

        $mail_data = [
            'recipient' => $request->email,
            'subject' => 'Reset Password',
            'body' => "Your reset code is: $code",
        ];

        Mail::to($mail_data['recipient'])->send(new ResetPasswordCode($mail_data));

        return back()->with('success', 'Reset code sent to your email.');
    }

    public function reset_password_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:schools,email',
            'code' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $lastCode = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastCode || $lastCode->code != $request->code) {
            return back()->withErrors(['code' => 'Invalid reset code.']);
        }

        $school = School::where('email', $request->email)->first();
        $school->password = Hash::make($request->password);
        $school->save();

        return redirect()->route('school.login.form')->with('success', 'Password reset successfully.');
    }
}
