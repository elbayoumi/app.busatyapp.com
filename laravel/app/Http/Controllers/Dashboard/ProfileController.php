<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Governorate;
use App\Models\City;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{

    public function dark_mode_update()
    {
        $user = Staff::find(auth()->guard('web')->user()->id);
        $user->dark_mode = !$user->dark_mode;
        $user->save();
        return $user->dark_mode;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Staff::find(auth()->guard('web')->user()->id);
        $permissions_groups = Permission::get()->groupBy('group');

        return view('dashboard.profile', compact([
            'user',
            'permissions_groups',

        ]));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'string', 'email', 'max:90', 'unique:staff,email,' .auth()->guard('web')->user()->id],
            'current_password' => ['required', 'string'],
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $user = Staff::findOrfail(auth()->guard('web')->user()->id);
        if (Hash::check($request->current_password, $user->password)) {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->logo) {

                if ($user->logo != 'default.png') {

                    Storage::disk('public_uploads')->delete('/staffs_logo/' . $user->logo);

                }//end of if

                Image::make($request->logo)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/staffs_logo/' . $request->logo->hashName()));

                    $user->logo  = $request->logo->hashName();

            }//end of if
            $user->save();

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
        return redirect()->back()->with('error', 'Current password not valied');

    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'current_pass' => ['required', 'string'],
        ]);


        $user = Staff::findOrfail(auth()->guard('web')->user()->id);

        if(Hash::check($request->current_pass, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->back()->with('success', 'success');
        }

        return redirect()->back()->with('error', 'Current password not valied');
    }

    public function info_update(Request $r, $id)
    {

        $user = Staff::findOrfail($id);
        $r->validate([

        ]);

        $user = Staff::findOrfail($id);
        $user->gender = $r->gender;
        $user->birth_date = $r->birth_date;
        $user->address_line_1 = $r->address_line_1;
        $user->address_line_1 = $r->address_line_1;
        $user->address_line_2 = $r->address_line_2;
        $user->governorate_id = $r->governorate_id;
        $user->city_id = $r->city_id;
        $user->save();


        return redirect()->back()->with('success', 'Staff data has been modified successfully');
    }

}
