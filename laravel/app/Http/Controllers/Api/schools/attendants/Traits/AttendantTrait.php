<?php

namespace App\Http\Controllers\Api\schools\attendants\Traits;

use App\Models\Attendant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait AttendantTrait
{

    private $createAttendantRoll = [
        'username' => 'required|string|unique:attendants|max:255', // Adjust max length as needed
        'password' => 'required|string|min:6', // Consider password complexity rules
        'name' => 'required|string|max:255',
        'gender_id' => 'nullable|integer|exists:genders,id', // Adjust if gender_id is a string
        'religion_id' => 'nullable|integer|exists:religions,id', // Adjust if religion_id is a string
        'type__blood_id' => 'nullable|integer|exists:type__bloods,id', // Adjust if type__blood_id is a string
        'bus_id' => 'nullable|integer|exists:buses,id', // Adjust if bus_id is a string
        'Joining_Date' => 'nullable|date',
        'address' => 'nullable|string',
        // 'city_name' => 'nullable|string',
        'phone' => 'required|string', // Consider phone format validation
        'birth_date' => 'nullable|date',
    ];
    private function updateAttendantRoll($id)
    {
        return [
            // 'email' => 'required|email|unique:my__parents,email,' . request()->user()->id,

            'username' => 'nullable|string|max:255|unique:attendants,username,' . $id,

            // 'username' => 'nullable|string|unique:attendants'.$id.'|max:255', // Adjust max length as needed
            'password' => 'nullable|string|min:6', // Consider password complexity rules
            'name' => 'nullable|string|max:255',
            'gender_id' => 'nullable|integer|exists:genders,id', // Adjust if gender_id is a string
            'religion_id' => 'nullable|integer|exists:religions,id', // Adjust if religion_id is a string
            'type__blood_id' => 'nullable|integer|exists:type__bloods,id', // Adjust if type__blood_id is a string
            'bus_id' => 'nullable|integer|exists:buses,id', // Adjust if bus_id is a string
            'Joining_Date' => 'nullable|date',
            'address' => 'nullable|string',
            // 'city_name' => 'nullable|string',
            'phone' => 'nullable|string', // Consider phone format validation
            'birth_date' => 'nullable|date',
        ];
    }
    private function processAttendant($r, $user, $type, $id = null)
    {
        // Fetch or create attendant based on ID
        if ($id != null) {
            $attendants = Attendant::where('id', $id)->where('school_id', $user->id)->first();
            if (!$attendants) {
                return response()->json(['errors' => true, 'message' => __('This supervisor does not exist or is not at your school')], 403);
            }
            $message = 'update';
        } else {
            $attendants = new Attendant;
            $attendants->type = $type;

            $message = 'add';
        }




        // Handle password update
        if ($r->has('password')) {
            $attendants->password = Hash::make($r->password);
        }
        if ($r->has('address')) {
            $attendants->address = $r->address;
        }
        if ($r->has('name')) {
            $attendants->name = $r->name;
        }
        $attendants->school_id = $r->user()->id;

        $attendants->gender_id = $r?->gender_id ?? 1;


        if ($r->has('username')) {
            $attendants->username = $r->username;
        }
        if ($r->has('phone')) {
            $attendants->phone = $r->phone;
        }
        // Handle file upload for logo
        if ($r->hasFile('logo')) {


            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/attendants_logo/' . $r->logo->hashName()));

            $attendants->logo  = $r->logo->hashName();
        }
        if ($r->has('bus_id')) {
            $attendants->bus_id = $r->bus_id;
        }

        // Save the attendant data
        $attendants->save();

        // Return response
        return response()->json(['data' => $attendants, 'errors' => false, 'message' => $type . ' ' . $message . ' successfully'], 200);
    }
    private function processCreateAttendant($r)
    {
        $validator = Validator::make($r->all(), $this->createAttendantRoll);
        // return response()->json( $r->user());

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }
        return false;
    }
    private function processUpdateAttendant($r, $id)
    {
        $validator = Validator::make($r->all(), $this->updateAttendantRoll($id));
        // return response()->json( $r->user());

        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }
        return false;
    }
}
