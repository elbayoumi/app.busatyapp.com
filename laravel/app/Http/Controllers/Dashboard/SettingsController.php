<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSmtpRequest;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:super|general-settings-edit'])->only(['general_settings_edit', 'general_settings_update']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function general_settings_edit()
    {
        $settings = Settings::first();
        return view('dashboard.settings.general', compact('settings'));
    }


    public function general_settings_update(Request $r)
    {

        $r->validate([

            'name' => ['nullable', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable'],


            'telephone' => ['nullable'],
            'mobile' => ['nullable'],
            'email' => ['nullable'],
            'whatsapp' => ['nullable'],


            'light_logo' => ['nullable'],
            'dark_logo' => ['nullable'],
            'favicon' => ['nullable'],
            'dashboard_logo' => ['nullable'],


        ]);

        $Settings = Settings::first();
        $Settings->name = ucfirst($r->name);
        $Settings->slogan = $r->slogan;
        $Settings->short_description = $r->short_description;
        $Settings->address = $r->address;
        $Settings->country = $r->country;
        $Settings->city = $r->city;
        $Settings->postal_code = $r->postal_code;
        $Settings->telephone = $r->telephone;
        $Settings->mobile = $r->mobile;
        $Settings->email = $r->email;
        $Settings->save();

        if($r->hasFile('light_logo')) {
            save_images($Settings, $r->light_logo, 'light_logo');
        }

        if($r->hasFile('dark_logo')) {
            save_images($Settings, $r->dark_logo, 'dark_logo');;
        }

        if($r->hasFile('favicon')) {
            // if (is_file('public/settings' . '/' . $Settings->favicon)) {
            //     Storage::delete('public/settings' . '/' . $Settings->favicon);
            // }
            save_images($Settings, $r->favicon, 'favicon');
        }

        if($r->hasFile('dashboard_logo')) {
            save_images($Settings, $r->dashboard_logo, 'dashboard_logo');
        }

        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function smtp()
    {
        $settings = Settings::first();
        return view('dashboard.settings.smtp', compact('settings'));
    }


    public function smtp_update(UpdateSmtpRequest $request)
    {

        // $r->validate([
        //     'smtp_host' => ['required'],
        //     'smtp_port' => ['required'],
        //     'smtp_encryption' => ['required'],
        //     'smtp_username' => ['required'],
        //     'smtp_password' => ['required'],
        //     'smtp_from_address' => ['required'],
        //     'smtp_from_name' => ['required'],
        // ]);

   // Retrieve the first settings record
   $settings = Settings::first();

   // Update settings using the update function
   $settings->update($request->validated());

        Artisan::call('config:clear');
        Artisan::call('cache:clear');




        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function send_test_email(Request $r)
    {
        $r->validate([
            'test_email' => ['required'],
        ]);

        try {
            Mail::to($r->test_email)->send(new TestMail());
            return redirect()->back()->with('success', 'Test done successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function merchants_app_edit()
    {
        $settings = Settings::first();
        return view('dashboard.settings.merchants_app', compact('settings'));
    }


    public function merchants_app_update(Request $r)
    {

        $r->validate([
            'merchants_latest_version' => ['required'],
        ]);

        $Settings = Settings::first();
        $Settings->merchants_latest_version = $r->merchants_latest_version;
        $Settings->save();


        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function distributors_app_edit()
    {
        $settings = Settings::first();
        return view('dashboard.settings.distributors_app', compact('settings'));
    }


    public function distributors_app_update(Request $r)
    {

        $r->validate([
            'distributors_latest_version' => ['required'],
        ]);

        $Settings = Settings::first();
        $Settings->distributors_latest_version = $r->distributors_latest_version;
        $Settings->save();


        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }

}
