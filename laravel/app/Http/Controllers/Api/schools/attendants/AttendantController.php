<?php

namespace App\Http\Controllers\Api\schools\attendants;

use App\Http\Controllers\Api\schools\attendants\Traits\AttendantTrait;
use App\Http\Controllers\Controller;
use App\Models\{
    School,
    Attendant,
    Bus,
    Gender,
    Religion,
    Type_Blood,
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Support\Facades\Storage,
    Validation\Rule,
};
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttendantController extends Controller
{
    use AttendantTrait;

    public function getAllDriver(Request $request)
    {
        try {
            $text = isset($request->text) && $request->text != '' ? $request->text : null;

            $attendants = Attendant::where('school_id', $request->user()->id)
                ->where('type', 'drivers')
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'trips',

                ]);

            if ($text != null) {
                $attendants = $attendants->where(function ($q) use ($request) {
                    return $q->when($request->text, function ($query) use ($request) {
                        return $query->where('name', 'like', '%' . $request->text . '%');
                    });
                });
            }

            $attendants =  $attendants->orderBy('id', 'desc')
                ->paginateLimit();

            return response()->json([
                'data' => $attendants,
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function getAllAdmins(Request $request)
    {
        try {
            $text = isset($request->text) && $request->text != '' ? $request->text : null;

            $attendants = Attendant::where('school_id', $request->user()->id)
                ->where('type', 'admins')
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'trips',

                ]);
            if ($text != null) {
                $attendants = $attendants->where(function ($q) use ($request) {
                    return $q->when($request->text, function ($query) use ($request) {
                        return $query->where('name', 'like', '%' . $request->text . '%');
                    });
                });
            }

            $attendants =  $attendants->orderBy('id', 'desc')
                ->paginateLimit();

            return response()->json([
                'data' => $attendants,
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function getShowDriver(Request $request, $id)
    {
        try {
            $attendant = Attendant::where('school_id', $request->user()->id)
                ->where('id', $id)
                ->where('type', 'drivers')
                ->with([
                    'gender',
                    'religion',
                    'typeBlood',
                    'trips'

                ])->first();

            if ($attendant) {
                return response()->json([
                    'data' => $attendant,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Driver not found'),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function getShowAdmins(Request $request, $id)
    {
        try {
            $attendant = Attendant::where('school_id', $request->user()->id)
                ->where('id', $id)
                ->where('type', 'admins')
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'trips',
                ])->first();

            if ($attendant) {
                return response()->json([
                    'data' => $attendant,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Admins not found'),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function create_driver(Request $r)
    {
        $user = $r->user();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();
        $trips = $user->trips();

        $data = [
            'school'   => $user,
            'genders'   => $genders,
            'typeBlood' => $typeBlood,
            'religion' => $religion,
            'trips' => $trips,

        ];

        if ($user != null) {
            return response()->json([
                'data' => $data,
                'message' => __("success message"),
                'status' => true
            ]);
        }

        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }
    public function getStoreDriver(Request $r)
    {
        try {
            if ($this->processCreateAttendant($r)) {
                return $this->processCreateAttendant($r);
            }

            return $this->processAttendant($r, $r->user(), 'drivers');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function create_admins(Request $r)
    {
        $user = $r->user();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();
        $trips = $user->trips();

        $data = [
            'school'   => $user,
            'genders'   => $genders,
            'typeBlood' => $typeBlood,
            'religion' => $religion,
            'trips' => $trips,

        ];

        if ($user != null) {
            return response()->json([
                'data' => $data,
                'message' => __("success message"),
                'status' => true
            ]);
        }

        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }
    public function getStoreAdmins(Request $r)
    {
        try {
            if ($this->processCreateAttendant($r)) {
                return $this->processCreateAttendant($r);
            }

            return $this->processAttendant($r, $r->user(), 'admins');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }




    public function edit_drive(Request $r, $id)
    {
        try {
            $userId = $r->user()->id;

            // 🔹 1) Find the driver (attendant) and make sure:
            // - He belongs to the same school
            // - His type is "drivers"
            // - His ID matches the provided $id
            $attendant = Attendant::query()
                ->where('school_id', $userId)
                ->where('type', 'drivers')
                ->whereKey($id)
                ->firstOrFail();

            // 🔹 2) Fetch reference data for dropdowns (select only what’s needed)
            $genders    = Gender::query()->select('id', 'name')->get();
            $typeBlood  = Type_Blood::query()->select('id', 'name')->get(); // If model name is TypeBlood, fix it
            $religion   = Religion::query()->select('id', 'name')->get();

            // 🔹 3) Get all trips belonging to this school (for assigning drivers)
            $trips = $r->user()->trips()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            // 🔹 4) Prepare structured response data
            $data = [
                // Return limited driver details only
                'attendant' => $attendant->only([
                    'id',
                    'name',
                    'phone',
                    'gender_id',
                    'type',
                    'license_no',
                    'license_expiry',
                    'national_id'
                ]),
                'school'    => [
                    'id'   => $r->user()->id,
                    'name' => $r->user()->name ?? null,
                ],
                'genders'   => $genders,
                'typeBlood' => $typeBlood,
                'religion'  => $religion,
                'trips'     => $trips,
            ];

            // 🔹 5) Return successful JSON response
            return response()->json([
                'data'    => $data,
                'message' => __('success message'),
                'status'  => true,
            ], 200);
        } catch (ModelNotFoundException $e) {
            // ⚠️ If driver not found or doesn’t belong to this school
            return response()->json([
                'data'    => null,
                'message' => __('Driver not found'),
                'status'  => false,
            ], 404);
        } catch (\Throwable $e) {
            // ⚠️ Unexpected errors (server/database issues)
            return response()->json([
                'data'    => null,
                'message' => $e->getMessage(),
                'status'  => false,
            ], 500);
        }
    }

    public function update_driver(Request $r, $id)
    {

        try {
            if ($this->processUpdateAttendant($r, $id)) {
                return $this->processUpdateAttendant($r, $id);
            }
            return $this->processAttendant($r, $r->user(), 'drivers', $id);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function edit_admins(Request $r, $id)
    {
        $attendant = Attendant::where('school_id', $r->user()->id)
            ->where('type', 'admins')
            ->where('id', $id)->first();
        $school =  School::where('id', $r->user()->id)->first();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();

        if ($attendant != null) {

            $data = [
                'attendant'   => $attendant,
                'school'   => $school,
                'genders'   => $genders,
                'typeBlood' => $typeBlood,
                'religion' => $religion,
            ];
            if ($school != null) {
                return response()->json([
                    'data' => $data,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }

            return response()->json([
                'message' => 'data not found',
                'status' => false,
            ], 400);
        }


        return response()->json(['errors' => true, 'message' => __("Admins not found")], 500);
    }

    public function update_admins(Request $r, $id)
    {
        try {
            if ($this->processUpdateAttendant($r, $id)) {
                return $this->processUpdateAttendant($r, $id);
            }
            return $this->processAttendant($r, $r->user(), 'admins', $id);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function delete(Request $request, $id)
    {

        try {

            $attendant = Attendant::where('id', $id)->first();


            $data = $request->all();
            $data['id'] = $id;
            $validator = Validator::make($data, [
                'id'               => ['required', Rule::exists('attendants', 'id')->where(function ($query) use ($request) {
                    return $query->where('school_id', $request->user()->id);
                })],


            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }

            if ($attendant->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/attendants_logo/' . $attendant->logo);
            } //end of if


            $attendant->delete();
            return JSON(__('Attendant deleted successfully'));
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
