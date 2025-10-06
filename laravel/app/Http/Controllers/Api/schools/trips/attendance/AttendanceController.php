<?php

namespace App\Http\Controllers\Api\schools\trips\attendance;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Trip;
use App\Models\Attendance;
use App\Models\Absence;
class AttendanceController extends Controller
{

    public function index(Request $r, $id)
    {
        try {
            $user = School::where('id', $r->user()->id)->first();
            if ($user != null) {

                $text = isset($r->text) && $r->text != '' ? $r->text : null;

                $trip = Trip::where('school_id', $user->id)->where('id', $id)->first();

                if ($trip != null) {

                    $student_request_absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
                        ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();
                    $attendances = Attendance::where('trip_id', $trip->id)->with(['students.grade']);

                    if ($text != null) {
                        $attendances = $attendances->where(function ($q) use ($r) {
                            return $q->when($r->text, function ($query) use ($r) {
                                return $query->whereHas('students', function ($e) use ($r) {
                                    $e->where('name', 'like', "%$r->text%");
                                });

                            });
                        });
                    }

                    $attendances = $attendances->orderBy('id', 'desc')->get();

                        return JSON([
                            'trips' => $trip,
                            'student_request_absence_ids' => array_unique($student_request_absence_ids),
                            'attendances' => $attendances,

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


    public function show(Request $r, $id)
    {
        try {
            $user = School::where('id', $r->user()->id)->first();
            if ($user != null) {

                    $attendance = Attendance::with(['students.grade']);
                        $attendance = $attendance->where(function ($q) use ($user) {
                                return $q->whereHas('students', function ($e) use ($user) {
                                    $e->where('school_id', $user->id);
                                });
                        });

                    $attendance = $attendance->where('id', $id)->orderBy('id', 'desc')->first();

                        return JSON([
                            'attendance' => $attendance,

                        ]);
                }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function update(Request $r, $id)
    {
        try {
            $user = School::where('id', $r->user()->id)->first();
            if ($user != null) {

                $validator = Validator::make($r->all(), [
                    'attendences'       => ['required','in:presence,absent,at_home'],

                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }

                    $attendance = Attendance::with(['students.grade']);
                        $attendance = $attendance->where(function ($q) use ($user) {
                                return $q->whereHas('students', function ($e) use ($user) {
                                    $e->where('school_id', $user->id);
                                });
                        });

                    $attendance = $attendance->where('id', $id)->orderBy('id', 'desc')->first();
                    if ($attendance != null && $attendance->trip->status == 0) {
                        if ($r->attendences == 'presence') {

                            $attendence_status = 1;

                        } else if ($r->attendences == 'absent') {

                            $attendence_status = 0;

                        } else if ($r->attendences == 'at_home' && $attendance->trip->trip_type == 'end') {

                            $attendence_status = 3;
                        } else {
                            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);

                        }
                        $attendance->update([
                            'attendence_status' => $attendence_status
                        ]);
                        return JSON([
                            'attendance' => $attendance,

                        ]);

                    }

                    return response()->json(['errors' => true, 'message' => __("You cannot edit the absence of a completed trip")], 500);



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


                    $attendance = Attendance::with(['students.grade']);
                        $attendance = $attendance->where(function ($q) use ($user) {
                                return $q->whereHas('students', function ($e) use ($user) {
                                    $e->where('school_id', $user->id);
                                });
                        });

                    $attendance = $attendance->where('id', $id)->orderBy('id', 'desc')->first();
                    if ($attendance != null && $attendance->trip->status == 0 && $attendance->trip->trip_type == 'end') {
                        $attendance->update([
                            'attendence_status' => 3,
                        ]);

                        return JSON([
                            'attendance' => $attendance,
                        ]);                    }
                        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
                }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }



}
