<?php

namespace App\Http\Controllers\Api\schools\trips;

use Carbon\Carbon;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\School;

use App\Models\Absence;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Enum\TripTypebBusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TripsController extends Controller
{
    public function index(Request $r)
    {
        $user = School::where('id', $r->user()->id)->first();
        if ($user != null) {
            $bus_id = isset($r->bus_id) && $r->bus_id != '' ? $r->bus_id : null;
            $trip_type = isset($r->trip_type) && $r->trip_type != '' ? $r->trip_type : null;
            $trips_date = isset($r->trips_date) && $r->trips_date != '' ? $r->trips_date : null;
            $status = isset($r->status) && $r->status != '' ? $r->status : null;

            $validator = Validator::make($r->all(), [
                'status' => 'in:completed,not_complete',

            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }

            $trips = Trip::query()->where('school_id', $user->id)->with(['bus', 'attendances', 'school']);

            if ($bus_id != null) {
                $trips = $trips->where(function ($q) use ($r) {
                    return $q->when(
                        $r->bus_id,
                        function ($query) use ($r) {
                            return $query->where('bus_id', $r->bus_id);
                        }
                    );
                });
            }
            if ($trip_type != null) {
                $trips = $trips->where(function ($q) use ($r) {
                    return $q->when(
                        $r->trip_type,
                        function ($query) use ($r) {
                            return $query->where('trip_type', $r->trip_type);
                        }
                    );
                });
            }
            if ($trips_date != null) {
                $trips = $trips->where(function ($q) use ($r) {
                    return $q->when(
                        $r->trips_date,
                        function ($query) use ($r) {
                            return $query->where('trips_date', $r->trips_date);
                        }
                    );
                });
            }
            if ($status != null) {
                $trips = $trips->where(function ($q) use ($r, $status) {
                    return $q->when(
                        $r->status,
                        function ($query) use ($r, $status) {

                            if ($status == 'completed') {
                                return $query->where('status', 1);
                            }
                            if ($status == 'not_complete') {
                                return $query->where('status', 0);
                            }
                        }
                    );
                });
            }

            $buses = Bus::query()->where('school_id', $user->id)->orderBy('id', 'desc')->get();
            $trips = $trips->orderBy('id', 'desc')->paginateLimit();
            return JSON([
                'trips' => $trips,
                'trips_count' => $trips->count(),
                'buses' => $buses,
            ]);
        }

        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }

    public function current(Request $request)
    {

        try {
            $tripArray = Trip::where('school_id', $request->user()->id)
                ->with('bus.attendants:id,name')
                ->where('trips_date', Carbon::now()->format('Y-m-d'))
                ->where('status', 0)
                ->get()
                ->toArray();

            return JSON($tripArray);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function currentByBus(Request $request, $bus_id)
    {

        try {
            $tripArray = Trip::where('bus_id', $bus_id)->where('school_id', $request->user()->id)
                ->with('bus.attendants:id,name')
                ->where('trips_date', Carbon::now()->format('Y-m-d'))
                ->where('status', 0)
                ->get()
                ->toArray();

            return JSON($tripArray);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = School::where('id', $request->user()->id)->first();
            if ($user != null) {
                $trip = Trip::where('id', $id)->where('school_id', $user->id)
                    ->with([
                        'bus',
                        'attendances.students.grade',
                    ])
                    ->first();

                if ($trip != null) {

                    $student_request_absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
                        ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();
                    return JSON([
                        'trip' => $trip,
                        'student_request_absence_ids' => array_unique($student_request_absence_ids),
                    ]);
                }
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function getTripOnMap(Request $request, $id)
    {
        $user = School::where('id', $request->user()->id)->first();

        if ($user != null) {


            $trip = Trip::where('school_id', $user->id)->where('id', $id)->first();

            if ($trip != null) {
                if ($trip->status == 0 && $trip->trips_date = date('Y-m-d')) {

                    $latitude = $trip->latitude;
                    $longitude = $trip->longitude;
                    return JSON([
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'trip' => $trip,
                    ]);
                }
            }
            return response()->json([
                'message' => __("trip is completed"),
                'status' => false,
            ], 500);
        }
        return response()->json([
            'message' => __("trip not found"),
            'status' => false,
        ], 500);
    }



    public function update(Request $r, $id)
    {
        try {
            $user = School::where('id', $r->user()->id)->first();
            if ($user != null) {
                $trip = Trip::where('id', $id)->where('school_id', $user->id)->first();
                if ($trip->trips_date == date('Y-m-d')) {


                    $validator = Validator::make($r->all(), [
                        'status' => ['in:completed,not_complete', 'required']

                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                    }


                    if ($r->status == 'completed') {
                        $status = 1;
                    } else {
                        $status = 0;
                    }

                    if ($status != $trip->status) {
                        if ($status == 0 && $trip->status == 1) {

                            if ($trip->trip_type == 'start') {
                                $students_at_school = Attendance::where('trip_id', $trip->id)->where('attendence_status', 2)->get();
                                if ($students_at_school->isNotEmpty()) {

                                    foreach ($students_at_school as $students_at_school) {
                                        $students_at_school->update([
                                            'attendence_status' => 1,

                                        ]);
                                    }
                                }
                            }

                            $trip->status = $status;
                        }

                        if ($status == 1 && $trip->status == 0) {
                            $students_attendence_ids = $trip->attendances->pluck('student_id');
                            $students_not_attendence = Student::where('bus_id', $trip->bus_id)
                                ->whereIn('trip_type', attendence_absence_type($trip->trip_type))
                                ->whereNotIn('id', $students_attendence_ids)
                                ->get();

                            if ($students_not_attendence->isNotEmpty()) {

                                foreach ($students_not_attendence as $not_attendence) {
                                    Attendance::create([
                                        'student_id' => $not_attendence->id,
                                        'attendence_date' => date('Y-m-d'),
                                        'school_id' => $trip->school_id,
                                        'attendence_status' => 0,
                                        'trip_id' => $trip->id,
                                    ]);
                                }
                            }

                            $students_presence = Attendance::where('trip_id', $trip->id)->where('attendence_status', 1)->get();

                            $attendence_status = 0;


                            if ($trip->trip_type == 'end') {

                                $attendence_status = 3;
                            }
                            if ($trip->trip_type == 'start') {
                                $attendence_status = 2;
                            }

                            if ($students_presence->isNotEmpty()) {

                                foreach ($students_presence as $student_presence) {
                                    $student_presence->update([
                                        'attendence_status' => $attendence_status,

                                    ]);
                                }
                            }
                            $trip->status = $status;
                        }
                    }
                    $trip->save();

                    return JSON([
                        'trip' => $trip,
                    ]);
                }

                return response()->json(['errors' => true, 'message' => __("You cannot edit trip data after the day has ended")], 500);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function end(Request $r, $id)
    {
        try {
            $user = School::where('id', $r->user()->id)->first();
            if ($user != null) {
                $trip = Trip::where('id', $id)->where('school_id', $user->id)->first();
                if ($trip->trips_date == date('Y-m-d')) {

                    if ($trip->status == 0) {
                        $students_attendence_ids = $trip->attendances->pluck('student_id');
                        $students_not_attendence = Student::where('bus_id', $trip->bus_id)
                            ->whereIn('trip_type', attendence_absence_type($trip->trip_type))
                            ->whereNotIn('id', $students_attendence_ids)
                            ->get();

                        if ($students_not_attendence->isNotEmpty()) {

                            foreach ($students_not_attendence as $not_attendence) {
                                Attendance::create([
                                    'student_id' => $not_attendence->id,
                                    'attendence_date' => date('Y-m-d'),
                                    'school_id' => $trip->school_id,
                                    'attendence_status' => 0,
                                    'trip_id' => $trip->id,
                                ]);
                            }
                        }


                        if ($trip->trip_type == 'end') {

                            $attendence_status = 3;
                        }
                        if ($trip->trip_type == 'start') {
                            $attendence_status = 2;
                        }
                        $students_presence = Attendance::where('trip_id', $trip->id)->where('attendence_status', 1)->get();

                        if ($students_presence->isNotEmpty()) {

                            foreach ($students_presence as $student_presence) {
                                $student_presence->update([
                                    'attendence_status' => $attendence_status,

                                ]);
                            }
                        }


                        if ($trip->trip_type == 'start') {
                            $students_at_school = Attendance::where('trip_id', $trip->id)->where('attendence_status', 1)->get();
                            if ($students_at_school->isNotEmpty()) {

                                foreach ($students_at_school as $students_at_school) {
                                    $students_at_school->update([
                                        'attendence_status' => 1,

                                    ]);
                                }
                            }
                        }
                        $trip->status = 1;
                        $trip->end_at = Carbon::now();
                    }


                    $trip->save();

                    return response()->json([
                        'data' => [
                            'trip' => $trip,

                        ],
                        'message' => 'success message',
                        'status' => true
                    ]);
                }

                return response()->json(['errors' => true, 'message' => __("You cannot edit trip data after the day has ended")], 500);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function showCurrentWaitingToSchool($bus_id)
    {
        try {
            $validator = Validator::make(['bus_id' => $bus_id], [
                'bus_id' => 'required|exists:buses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $current_date = Carbon::now()->format('Y-m-d');


            $waiting = Student::where('bus_id', $validated['bus_id'])
                ->whereDoesntHave('absences', function ($q) use ($current_date) {
                    $q->where('attendence_date', $current_date)->where('attendence_type', "<>", 'end_day');
                })
                ->whereDoesntHave('attendance', function ($q) use ($current_date) {
                    //0 is on bus and 1 arrived to school
                    $q->where('attendence_date', $current_date)->where('attendance_type', 'start_day')->where('attendence_status', 0);
                })
                ->get();

            return response()->json([
                'waiting' => $waiting,
                'message' => __("success message"),
                'status' => true,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function showCurrentPresentOnBusToSchool($bus_id)
    {
        try {

            $validator = Validator::make(['bus_id' => $bus_id], [
                'bus_id' => 'required|exists:buses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $current_date = Carbon::now()->format('Y-m-d');

            $present_on_bus = Student::where('bus_id', $validated['bus_id'])->whereHas('attendance', function ($q) use ($current_date) {
                //0 is on bus and 1 arrived to school
                $q->where('attendence_date', $current_date)->where('attendance_type', 'start_day')->where('attendence_status', 0);
            })->orderBy('id', 'desc')->get();

            $trip = Trip::where("bus_id", $validated['bus_id'])->startDay()->notCompleted();
            return response()->json([
                'present_on_bus' => $present_on_bus,
                'trip' => $trip->exists(),
                'data' => $trip?->first(),
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function showCurrentAbsencessStartDay($bus_id)
    {
        try {
            $validator = Validator::make(['bus_id' =>  $bus_id], [
                'bus_id' => 'required|exists:buses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $current_date = Carbon::now()->format('Y-m-d');

            $absences = Student::select('id', 'name', 'bus_id')->where('bus_id', $validated['bus_id'])
                ->whereHas('absences', function ($q) use ($current_date) {
                    $q->where('attendence_date', $current_date)->where('attendence_type', "<>", 'end_day');
                })->orderBy('id', 'desc')->get();

            return response()->json([
                'absences' => $absences,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function showCurrentPresentOnBusToHome($trip_id)
    {
        try {

            $validator = Validator::make(['trip_id' => $trip_id], [
                'trip_id' => 'required|exists:trips,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $trip_id = $validated['trip_id'];

            $trip = Trip::whereId($trip_id)->notCompleted();
            if (!$trip->exists()) {
                return response()->json([
                    'message' => __("success message"),
                    'trip' => $trip->exists(),
                    'errors' => true,
                ], 404);
            }
            $present_on_bus = Attendance::onBus($trip_id);

            return response()->json([
                'present_on_bus' => $present_on_bus,
                'message' => __("success message"),
                'trip' => $trip->exists(),
                'errors' => false,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),

                'status' => false
            ], 500);
        }
    }

    public function showCurrentArrivedToHome($bus_id)
    {
        try {
            $data = ['bus_id' => $bus_id];

            $validator = Validator::make($data, [
                'bus_id' => 'required|exists:buses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $attendance_type = 'end_day';
            $attendence_status = 1;

            $current_date = Carbon::now()->format('Y-m-d');

            $trip_id = Trip::where('bus_id', $data['bus_id'])
                ->where('trip_type', TripTypebBusEnum::from($attendance_type)->value)
                ->notCompleted();

            if ($trip_id->exists()) {
                $trip_id = $trip_id->first();
                $trip_id = $trip_id->id;

                $arrived = Student::where('bus_id', $data['bus_id'])
                    ->whereHas('attendance', function ($q) use ($current_date, $attendance_type, $attendence_status, $trip_id) {
                        //0 is on bus and 1 arrived to school
                        $q->where('attendence_date', $current_date)->where('attendance_type', $attendance_type)
                            ->where('attendence_status', $attendence_status)->where('trip_id', $trip_id);
                    })->orderBy('id', 'desc')->get();

                return response()->json([
                    'arrived' => $arrived,
                    'message' => __("success message"),
                    'errors' => false,
                ]);
            } else {
                return response()->json([
                    'message' => __("No trips found."),
                    'errors' => false,
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function showCurrentAbsencessEndDay($bus_id)
    {
        try {
            $validator = Validator::make(['bus_id' =>  $bus_id], [
                'bus_id' => 'required|exists:buses,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $current_date = Carbon::now()->format('Y-m-d');

            $absences = Student::where('bus_id', $validated['bus_id'])->whereHas('absences', function ($q) use ($current_date) {
                $q->where('attendence_date', $current_date)->whereIn('attendence_type', ['end_day', 'full_day']);
            })->orderBy('id', 'desc')->get();

            return response()->json([
                'absences' => $absences,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function showPreviousTrips(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bus_id' => 'sometimes|exists:buses,id',
                'date' => 'sometimes|date_format:Y-m-d',
                'type' => 'sometimes|in:start_day,end_day',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 422);
            }

            $filters = $validator->validated() ?? [];

            $previous_trips = Trip::where('school_id', request()->user()->id)
                ->where(function ($q) use ($filters) {
                    $q->where('end_at', '<', Carbon::now())
                        ->orWhere('status', 1); // status == completed
                })
                ->when($filters != [], function ($q) use ($filters) {
                    $q->filter($filters);
                })
                ->with(['bus:id,name', 'attendants:id,type,name'])
                ->get()
                ->each(function ($trip) {
                    if ($trip->relationLoaded('bus')) {
                        $trip->bus->setAppends([]);
                    }
                });

            return response()->json([
                'previous_trips' => $previous_trips,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    // show previous trip absents
    public function showPreviousTripAbsents(Request $request, $trip_id)
    {
        try {
            $trip = Trip::where('id', $trip_id)
                ->where('school_id', $request->user()->id)
                ->first();

            if (!$trip) {
                return response()->json([
                    'message' => __("trip not found"),
                    'status' => false,
                ], 404);
            }

            // Fetch students where pivot status is 'absent'
            $absentStudents = $trip->students()
                ->wherePivot('status', 'absent')
                ->get();

            return response()->json([
                'absentStudents' => $absentStudents,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    // show previous trip attendants
    public function showPreviousTripAttendants(Request $request, $trip_id)
    {
        // attendant students
        try {
            $trip = Trip::where('id', $trip_id)
                ->where('school_id', $request->user()->id)
                ->first();

            if (!$trip) {
                return response()->json([
                    'message' => __("trip not found"),
                    'status' => false,
                ], 500);
            }

            $attendantStudents = $trip?->students()->wherePivot('trip_students.status','<>','absent')?->get() ?? [];

            return response()->json([
                'attendantStudents' => $attendantStudents,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
}
