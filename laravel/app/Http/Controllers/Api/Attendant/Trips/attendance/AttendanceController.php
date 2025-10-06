<?php

namespace App\Http\Controllers\Api\Attendant\Trips\attendance;

use Carbon\Carbon;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\School;
use App\Models\Absence;
use App\Models\Student;

use App\Models\Attendant;
use App\Models\Attendance;
use App\Models\TripStudent;

use App\Enum\TripStatusEnum;
use Illuminate\Http\Request;
use App\Enum\TripTypebBusEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\StudentNotification;
use Illuminate\Support\Facades\Notification;

class AttendanceController extends Controller
{

    public function index(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }
            if ($user != null && $bus != null) {

                $text = isset($r->text) && $r->text != '' ? $r->text : null;

                $trip = Trip::where('bus_id', $user->bus_id)->where('id', $id)->first();

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

                    return response()->json([
                        'data' => [
                            'trips' => $trip,
                            'student_request_absence_ids' => array_unique($student_request_absence_ids),

                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }
//  function index(Request $r)
//  {
//     try {
//         $bus = $r->user()->bus()->with(['trip'])->first();

//                 $student_request_absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($bus->trip->trip_type))
//                     ->where('bus_id', $bus->trip->bus_id)->where('attendence_date', $bus->trip->trips_date)->pluck('student_id')->toArray();
//                 $attendances = Attendance::where('trip_id', $bus->trip->id)->with(['students.grade']);

//                 if (!empty($r->text)  ) {
//                     $attendances = $attendances->where(function ($q) use ($r) {
//                         return $q->when($r->text, function ($query) use ($r) {
//                             return $query->whereHas('students', function ($e) use ($r) {
//                                 $e->where('name', 'like', "%$r->text%");
//                             });
//                         });
//                     });
//                 }

//                 $attendances = $attendances->orderBy('id', 'desc')->get();

//                 return response()->json([
//                     'data' => [
//                         'trips' => $bus->trip,
//                         'student_request_absence_ids' => array_unique($student_request_absence_ids),

//                     ],
//                     'message' => __("success message"),
//                     'status' => true
//                 ]);
// } catch (\Exception $exception) {
//         return response()->json([
//             'message' => $exception->getMessage(),
//             'status' => false
//         ], 500);
//     }
// }

    public function show(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }
            if ($user != null && $bus != null) {

                $attendance = Attendance::with(['students.grade']);
                $attendance = $attendance->where(function ($q) use ($user) {
                    return $q->whereHas('students', function ($e) use ($user) {
                        $e->where('bus_id', $user->bus_id);
                    });
                });

                $attendance = $attendance->where('id', $id)->orderBy('id', 'desc')->first();

                return response()->json([
                    'data' => [
                        'attendance' => $attendance,


                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function create(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }
            if ($user != null && $bus != null) {

                $data = [];
                $data['trip_id'] = $id;

                $validator = Validator::make($data, [
                    'trip_id' => [
                        'required', Rule::exists('trips', 'id')->where(function ($query) use ($r, $user) {
                            return $query->where('trips_date', date('Y-m-d'))->where('bus_id', $user->bus_id)->where('status', 0);
                        }),
                        [
                            'trip_id.required' => __('The trip must already exist and be dated for today')
                        ]
                    ],

                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }


                $trip = Trip::where('id', $id)->where('bus_id', $user->bus_id)->first();
                $students_bus = Student::with(['attendance'])
                    ->where('bus_id', $user->bus_id)->get();
                $students_has_attendance_in_trip = Student::where('bus_id', $user->bus_id);
                $students_has_attendance_in_trip = $students_has_attendance_in_trip->where(function ($q) use ($trip) {
                    return $q->whereHas('attendance', function ($e) use ($trip) {
                        $e->where('trip_id', $trip->id);
                    });
                });

                $students_has_attendance_in_trip_ids = $students_has_attendance_in_trip->pluck("id");

                $students_has_absence_in_trip_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
                    ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();

                return response()->json([
                    'data' => [
                        'trip' => $trip,
                        'students_bus' => $students_bus,
                        'students_has_attendance_in_trip_ids' => $students_has_attendance_in_trip_ids,
                        'students_has_absence_in_trip_ids' => $students_has_absence_in_trip_ids,

                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function store(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $bus != null) {
                $trip = Trip::where('id', $id)->first();
                if ($trip != null && $trip->status != 0) {
                    return response()->json(['errors' => true, 'message' => __('Absences cannot be added after the trip has ended')], 500);
                }

                $validator = Validator::make($r->all(), [
                    'attendences' => ['required', 'array', 'min:1'],
                    'attendences.*.id' => ['required', 'exists:students,id', Rule::unique('attendances', 'student_id')->where(function ($query) use ($r, $trip) {
                        return $query->where('trip_id', $trip->id);
                    })],
                    'attendences.*.status' => ['required', 'in:presence,absent'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }

                foreach ($r->attendences as $attendance) {
                    $studentId = $attendance['id'];
                    $attendanceStatus = $attendance['status'];

                    Attendance::create([
                        'student_id' => $studentId,
                        'attendence_date' => date('Y-m-d'),
                        'school_id' => $trip->school_id,
                        'attendence_status' => ($attendanceStatus == 'presence') ? 1 : 0,
                        'trip_id' => $trip->id,
                    ]);
                }

                return response()->json(['errors' => false, 'message' => __('Data has been added successfully')], 200);
            }

            return response()->json(['errors' => true, 'message' => __('At least one item must be added')], 500);
        } catch (\Exception $e) {
            return response()->json(['errors' => true, 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }


            if ($user != null && $bus != null) {

                $validator = Validator::make($r->all(), [
                    'attendences' => ['required', 'in:presence,absent,at_home'],

                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }

                $attendance = Attendance::with(['students']);
                $attendance = $attendance->where(function ($q) use ($user) {
                    return $q->whereHas('students', function ($e) use ($user) {
                        $e->where('bus_id', $user->bus_id);
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
                    return response()->json([
                        'data' => [
                            'attendance' => $attendance,

                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }

                return response()->json(['errors' => true, 'message' => __('Cannot modify the absence of a completed trip')], 500);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function end(Request $r, $id)
    {
        try {
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();
            }


            if ($user != null && $bus != null) {


                $attendance = Attendance::with(['students']);
                $attendance = $attendance->where(function ($q) use ($user) {
                    return $q->whereHas('students', function ($e) use ($user) {
                        $e->where('bus_id', $user->bus_id);
                    });
                });

                $attendance = $attendance->where('id', $id)->orderBy('id', 'desc')->first();
                if ($attendance != null && $attendance->trip->status == 0 && $attendance->trip->trip_type == 'end') {
                    $attendance->update([
                        'attendence_status' => 3,
                    ]);

                    return response()->json([
                        'data' => [
                            'attendance' => $attendance,

                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
                return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function present_on_bus_start_day(Request $request, $student_id)
    {
        try {
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->startDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['onboard_at' => now()]);
              
            $startTime = microtime(true);
            $student=Student::find($student_id);
            $body = "{$student->name} ركب الباص وفي طريقه الى المدرسة";
            $notifications_type='tracking';

            Notification::send($student, new StudentNotification('صباح الخير', $body,$notifications_type));
            $endTime = microtime(true);

            // Calculate the duration in seconds
            $executionTime = $endTime - $startTime;
            $removeAbsence=removeAbsence(new Absence,$request,$student_id,'start_day');

            $attendance = $this->attendanceProcessAction($request, 'start_day', 0, $student_id);




            Log::info('process Notification sent in ' . $executionTime . ' seconds.');

            return response()->json([
                'data' => [
                    'attendance' => $attendance,
                    'removeAbsence' =>$removeAbsence,
                ],
                'message' => __("success message"),
                'status' => true
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function present_all_on_bus_end_day(Request $r)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');
            $waiting = Student::where('bus_id', $r->user()->bus_id)->whereIn('trip_type', ['end_day', 'full_day'])->whereDoesntHave('absences', function ($query) use ($current_date) {
                $query->where('attendence_date', $current_date)->whereIn('attendence_type', ['full_day', 'end_day']);
            })->whereDoesntHave('attendance', function ($query) use ($current_date) {
                $query->where('attendence_date', $current_date)->whereIn('attendance_type', ['full_day', 'end_day'])->where('attendence_status', 0);
            })->get();

            return response()->json([
                'waiting' => $waiting,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }
    private function attendanceProcessAction($request, $attendance_type, $attendence_status, $student_id)
    {

        $attendence_date = Carbon::now()->format('Y-m-d');

        $attendance = Attendance::where('student_id', $student_id)->where('attendence_date', $attendence_date)->where('attendance_type', $attendance_type)->where('attendence_status', $attendence_status);
        $trip = Trip::where('bus_id', $request->user()->bus_id)->where('trips_date', $attendence_date)->where('status', TripStatusEnum::from($attendence_status)->value)->where('trip_type',TripTypebBusEnum::from($attendance_type)->value );
        if (!$trip->exists()) {
            return response()->json([
                'message' => __('The trip must not be over'),
                'status' => false
            ], 403);
        }
        $trip=$trip->first();
        if (!$attendance->exists()) {
            $attendance = new Attendance();
            $attendance->school_id = $request->user()->school_id;
            $attendance->student_id = $student_id;
            $attendance->attendence_date = $attendence_date;
            $attendance->trip_id = $trip->id;
            $attendance->attendence_status = $attendence_status;
            $attendance->attendance_type = $attendance_type;

            $attendance->save();
            return $attendance;
        } else {
            return __('This student already exists');
        }
    }
}
