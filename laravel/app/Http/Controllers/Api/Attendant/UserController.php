<?php

namespace App\Http\Controllers\Api\Attendant;

use App\Http\Controllers\Controller;
use App\Models\Attendant;
use App\Models\School;
use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Governorate;
use App\Models\City;
use App\Models\My_Parent;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{



    public function data_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string',
            'username' => 'required|unique:attendants,username,' . request()->user()->id,
            'address' => 'required',
            'current_password' => 'required|string',
            'name' => 'required',
            'phone' =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }

        $User = Attendant::findOrfail($request->user()->id);
        if (Hash::check($request->current_password, $User->password)) {
            // $User->name = $request->name;
            $User->username = $request->username;
            $User->address = $request->address;
            $User->name = $request->name;
            $User->phone = $request->phone;

            if ($request->logo) {

                if ($User->logo != 'default.png') {

                    Storage::disk('public_uploads')->delete('/attendants_logo/' . $User->logo);

                }//end of if

                Image::make($request->logo)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/attendants_logo/' . $request->logo->hashName()));

                    $User->logo  = $request->logo->hashName();

            }//end of if
            $User->save();


            $User = Attendant::findOrfail($request->user()->id);
            return res($User, 1, __('Data has been updated successfully'));
        }
        return res($User, 0, __('The current password is incorrect'));

    }





    public function password_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'current_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }

        $User = Attendant::findOrfail($request->user()->id);

        if(Hash::check($request->current_password, $User->password)){
            $User->password = $request->password;
            $User->save();
            // Auth::logoutOtherDevices($currentPassword);

            return res(null, 1, 'تم تغير كلمة السر بنجاح');
        }
        return res(null, 0, 'كلمة السر الحالية غير صحيحة');
    }


}
