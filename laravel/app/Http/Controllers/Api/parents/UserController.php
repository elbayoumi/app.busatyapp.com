<?php

namespace App\Http\Controllers\Api\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\My_Parent;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{



    public function data_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string',
            'email' => 'required|email|unique:my__parents,email,' . request()->user()->id,
            'address' => 'required',
            'current_password' => 'required|string',
            'name' => 'required',
            'phone' =>  'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000', // Adjust the file types and size limit as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }

        $user = My_Parent::findOrfail($request->user()->id);
        if (Hash::check($request->current_password, $user->password)) {
            // $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->name = $request->name;
            $user->phone = $request->phone;

            $this->uploadLogo($request);

            $user->save();
            $user->refresh();


            $user = My_Parent::findOrfail($request->user()->id);
            return res($user, 1, 'تم تغير البيانات بنجاح');
        }
        return res($user, 0, 'كلمة السر الحالية غير صحيحة');
    }
    public function complete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4|max:60',
                'address'=> 'nullable|min:5|max:100',
                'phone' => 'nullable|string|min:10|max:20|unique:my__parents,phone,' . $request->user()->id,
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => true,
                    'messages' => $validator->errors()
                ], 422);
            }

            // Get the authenticated parent user
            $user = $request->user();

            // Check current password before allowing changes


            // Update only the fields that are present in the request


            if ($request->filled('address')) {
                $user->address = $request->address;
            }

            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }

            // Handle logo upload
            if ($request->hasFile('logo')) {
                if ($user->logo && $user->logo !== 'default.png') {
                    Storage::disk('public_uploads')->delete('/parents_logo/' . $user->logo);
                }

                Image::make($request->logo)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/parents_logo/' . $request->logo->hashName()));

                $user->logo = $request->logo->hashName();
            }

            $user->save();

            // Refresh and check missing fields
            $user->refresh();

            // return response()->json([
            //     'data' => $user,
            //     'message' => 'Data updated successfully',
            // ]);
            return JSON($user);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    private function uploadLogo(Request $request)
    {
        if ($request->hasFile('logo')) {
            $user = $request->user(); // Assuming you are getting the user instance

            // Delete the previous logo if it's not the default one
            if ($user->logo && $user->logo != 'default.png') {
                Storage::disk('public_uploads')->delete('/parents_logo/' . $user->logo);
            }

            // Resize and save the uploaded image
            $image = $request->file('logo');
            $path = public_path('uploads/parents_logo/' . $image->hashName());

            Image::make($image)
                ->resize(300, 200) // Adjust dimensions as needed
                ->save($path, 70); // Adjust quality as needed

            // Update the user's logo field with the new image file name
            $user->logo = $image->hashName();
            $user->save();

            return 'Logo uploaded and resized successfully!';
        }

        return 'No logo uploaded.';
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

        $User = My_Parent::findOrfail($request->user()->id);

        if (Hash::check($request->current_password, $User->password)) {
            $User->password = $request->password;
            $User->save();

            // Auth::logoutOtherDevices($currentPassword);
            return res(null, 1, 'تم تغير كلمة السر بنجاح');
        }
        return res(null, 0, 'كلمة السر الحالية غير صحيحة');
    }
}
