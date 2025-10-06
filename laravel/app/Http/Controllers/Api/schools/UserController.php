<?php

namespace App\Http\Controllers\Api\schools;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{



    public function data_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:schools,email,' . request()->user()->id,
                'address' => 'required',
                'city_name' => 'required',
                'current_password' => 'required|string',
                'name' => 'required',
                'phone' =>  'required|unique:schools,phone,' . request()->user()->id,
                'latitude' => 'required|between:-90,90',
                'longitude' => 'required|between:-180,180'
            ]);

            if ($validator->fails()) {
                // return res(null, 0, $validator->errors());
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }

            $user = School::findOrfail($request->user()->id);
            if (Hash::check($request->current_password, $user->password)) {
                // $user->name = $request->name;
                $user->email = $request->email;
                $user->address = $request->address;
                $user->city_name = $request->city_name;
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->latitude = $request->latitude;
                $user->longitude = $request->longitude;

                if ($request->logo) {

                    if ($user->logo != 'default.png') {

                        Storage::disk('public_uploads')->delete('/schools_logo/' . $user->logo);
                    } //end of if

                    Image::make($request->logo)
                        ->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('uploads/schools_logo/' . $request->logo->hashName()));

                    $user->logo  = $request->logo->hashName();
                } //end of if
                $user->save();
                $user->refresh();


                // $user = School::findOrfail($request->user()->id);
                return res($user, 1, __("Data updated successfully"));
            }
            return res($user, 0, __("Current password is incorrect"), 403);
        } catch (\Throwable $th) {
            return res(null, 'somthing error', $th->getMessage(), 500);
        }
    }

    public function notificationSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'trip_start_end_notification_status' => 'required|in:0,1',
                'student_absence_notification_status' => 'required|in:0,1',
                'student_address_notification_status' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => true,
                    'messages' => $validator->errors()
                ], 422);
            }

            $user = School::findOrFail($request->user()->id);
            $user->trip_start_end_notification_status = $request->trip_start_end_notification_status;
            $user->student_absence_notification_status = $request->student_absence_notification_status;
            $user->student_address_notification_status = $request->student_address_notification_status;
            $user->save();
            
            return res($user, 1, __("Data updated successfully"));
        } catch (\Exception $e) {
            return response()->json( ['error' => true, 'message' => $e->getMessage()], 500);
        }
    }



    public function complete(Request $request)
    {
        try {
            // Validate input (nullable, only validate if present)
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|min:4|max:60',
                'address' => 'nullable|min:5|max:50',
                'city_name' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'phone' => 'nullable|string|min:10|max:20|unique:schools,phone,' . $request->user()->id,
                'logo' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => true,
                    'messages' => $validator->errors()
                ], 422);
            }

            // Get the current authenticated school user
            $user = School::findOrFail($request->user()->id);

            // Update only the fields that were sent in the request
            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            if ($request->filled('address')) {
                $user->address = $request->address;
            }

            if ($request->filled('city_name')) {
                $user->city_name = $request->city_name;
            }

            if ($request->filled('latitude')) {
                $user->latitude = $request->latitude;
            }

            if ($request->filled('longitude')) {
                $user->longitude = $request->longitude;
            }

            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }

            // Handle logo upload if present
            if ($request->hasFile('logo')) {
                if ($user->logo && $user->logo != 'default.png') {
                    Storage::disk('public_uploads')->delete('/schools_logo/' . $user->logo);
                }

                Image::make($request->logo)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/schools_logo/' . $request->logo->hashName()));

                $user->logo = $request->logo->hashName();
            }

            // Save the updated user
            $user->save();
            $user->refresh();

            // Check which required fields are still empty

            // Return response
            return JSON($user);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function password_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
                'current_password' => 'required|string',
            ]);


            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }

            $User = School::findOrfail($request->user()->id);

            if (Hash::check($request->current_password, $User->password)) {
                $User->password = $request->password;
                $User->save();
                // Auth::logoutOtherDevices($currentPassword);

                return res(null, 1, __("Password changed successfully"));
            }
            return response()->json(['errors' => true, 'message' => __("Current password is incorrect")], 403);
        } catch (\Throwable $th) {
            return res(null, 'somthing error', $th->getMessage(), 500);
        }
    }
}
