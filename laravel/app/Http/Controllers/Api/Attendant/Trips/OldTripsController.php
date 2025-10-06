<?php

namespace App\Http\Controllers\Api\Attendant\Trips;

use App\Http\Controllers\Controller;
use App\Models\Attendant;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Bus;
use App\Models\Student;
use App\Models\Trip;
use App\Models\Attendance;
use App\Models\AttendantParentMessage;
use App\Models\My_Parent;
use Illuminate\Support\Facades\DB;
use App\Models\Absence;
use Illuminate\Validation\Rule;
use App\Events\MyEvent;


class OldTripsController extends Controller
{

    public function getAll(Request $request)
    {
        $user = Attendant::where('id', $request->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }


        $trips = Trip::with(['attendances.students.grade'])
        ->where('bus_id', $buses->id)
        ->orderBy('id', 'desc')->paginateLimit();

        if ($user != null && $buses != null && $trips != null) {
            return response()->json([
                'data' => [
                    'trips' => $trips,
                    'trips_count' => $trips->count(),
                    'bus' => $buses,


                ],
                'message' => 'success message',
                'status' => true
            ]);
        }

    }


    public function getStartTripFromDay(Request $request)
    {



        $user = Attendant::where('id', $request->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }



        $today = now()->toDateString();


        $trip = Trip::where('trip_type', 'start')->with(['attendances.students.grade'])
        ->where('bus_id', $buses->id)
        ->whereDate('created_at', $today)
        ->orderBy('id', 'desc')->first();


        if ($user != null && $buses != null && $trip != null) {
            return response()->json([
                'data' => [
                    'trip' => $trip,
                    'bus' => $buses,


                ],
                'message' => 'success message',
                'status' => true
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Something was wrong'], 500);

    }

    public function getEndTripFromDay(Request $request)
    {

        $user = Attendant::where('id', $request->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        $today = now()->toDateString();


        $trip = Trip::where('trip_type', 'end')->with(['attendances.students.grade'])
        ->where('bus_id', $buses->id)
        ->whereDate('created_at', $today)
        ->orderBy('id', 'desc')->first();


        if ($user != null && $buses != null && $trip != null) {
            return response()->json([
                'data' => [
                    'trip' => $trip,
                    'bus' => $buses,


                ],
                'message' => 'success message',
                'status' => true
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Something was wrong'], 500);

    }


    public function getShow(Request $request, $id)
    {
        try {


            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $bus= Bus::where('id', $user->bus_id)->first();

            }

            if ($user != null && $user->type == 'admins') {

                $bus = Bus::where('id', $user->bus_id)->first();

            }




            if ($bus != null) {
                $trip = Trip::where('id', $id)->where('bus_id', $bus->id)
                ->with([
                    'bus',
                    'attendances.students.grade',
                    'school',
                ])
                ->first();

                if ($trip != null) {

                    $absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
                    ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();


                    return response()->json([
                        'data' => [
                            'students_absence_ids'   => $absence_ids,
                            'trip' => $trip,

                        ],
                        'message' => 'success message',
                        'status' => true
                    ], 200);
                }


            }

            return response()->json([
                'message' => 'trip not found',
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
               'message' => $exception->getMessage(),
               'status' => false
            ], 500);
        }
    }




    public function store(Request $r) {
        try {

        $user = Attendant::where('id', $r->user()->id)->first();
        if (isset($user) != null && $user->bus_id != null) {

            $data = $r->all();
            $data['trips_date'] = date('Y-m-d');
            $validator = Validator::make($data, [
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'trip_type'          => ['required', 'in:start,end',Rule::unique('trips')->where(function ($query) use ($r, $user) {
                    return $query->where('trips_date', date('Y-m-d'))->where('bus_id', $user->bus_id);
                })],
                'trips_date'          => ['required',Rule::unique('trips', 'trips_date')->where(function ($query) use ($r, $user) {
                    return $query->where('bus_id', $user->bus_id)->where('status', 0);
                })],
            ],[
                'trips_date.*' => 'يجب اغلاق الرحلات باتاريخ اليوم اولا'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }


            $trip = new Trip;
            $trip->latitude = $r->latitude;
            $trip->longitude = $r->longitude;
            $trip->bus_id = $user->bus_id;
            $trip->school_id = $user->school_id;;
            $trip->trips_date = date('Y-m-d');
            $trip->trip_type = $r->trip_type;
            $trip->save();
            if ($user != null && $trip != null) {
                return response()->json([
                    'data' => [
                        'trip' => $trip,

                    ],
                    'message' => 'success message',
                    'status' => true
                ],200);
            }

        }

        return response()->json([
            'message' => 'data not found',
            'status' => false,
        ], 404);

    } catch (\Exception $exception) {
        return response()->json([
           'message' => $exception->getMessage(),
           'status' => false
        ], 500);
    }
    }





    public function  create_attendance_start(Request $request)
    {
        try {

            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $buses = Bus::where('attendant_driver_id', $user->id)->first();

            }

            if ($user != null && $user->type == 'admins') {

                $buses = Bus::where('attendant_admins_id', $user->id)->first();

            }

            $school =  School::where('id', $buses->school_id)->first();
            $students = Student::where('bus_id', $buses->id)->where('school_id', $school->id)->where('trip_type', '!=', 'end_day')->orderBy('id', 'desc')
            ->with([
                'schools',
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
                'my_Parents',
                'attendant_admins',
                'attendant_driver',
                'attendance',
                'absences',
            ])->get();

            $type = 'start';
            $trip = Trip::where('bus_id', $buses->id)->where('trips_date', date('Y-m-d'))->where('trip_type', $type)->where('status', 0)->first();
            $attendance = Attendance::where('type', 'start')->where('bus_id', $buses->id)->where('attendence_date', date('Y-m-d'))->get();
            $absences = Absence::where('attendence_type', '!=', 'end_day')->where('bus_id',  $buses->id)->where('school_id', $buses->school_id)->where('attendence_date', date('Y-m-d'))->pluck('student_id');
            // $table->enum('attendence_type', ['full_day', 'end_day', 'start_day'])->nullable();

            $stids = $students->pluck('id');



            $students_presence = Student::where('bus_id', $buses->id)->where('school_id', $school->id)->where('trip_type', '!=', 'end_day')->orderBy('id', 'desc')
            ->with([
                'schools',
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
            ])
            ->whereNotIn('id', $absences)
            ->get();

            $students_absent = Student::where('bus_id', $buses->id)->where('school_id', $school->id)->where('trip_type', '!=', 'end_day')->orderBy('id', 'desc')
            ->with([
                'schools',
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
            ])
            ->whereIn('id', $absences)
            ->get();

            if ($user != null && $buses != null && $trip != null) {
                return response()->json([
                    'data' => [
                        'school' => $school,
                        'students_presence' => $students_presence,
                        'students_absent' => $students_absent,
                        'buses' => $buses,
                        'trip' => $trip,
                        'attendance' => $attendance,
                        'trip_type' => $type,
                        'trip_students_all_count' => $students->count(),
                        'attendance_trip_students_all_count' => $attendance->whereIn('student_id', $stids)->count(),
                        'attendance_trip_students_presence_count' => $attendance->whereIn('student_id', $stids)->where('attendence_status', true)->count(),
                        'attendance_trip_students_absent_count' => $attendance->whereIn('student_id', $stids)->where('attendence_status', false )->count(),


                    ],
                    'message' => 'success message',
                    'status' => true
                ]);
            }

            return response()->json([
                'message' => 'trip data not found',
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return response()->json([
               'message' => $exception->getMessage(),
               'status' => false
            ], 500);
        }
    }

    public function  create_attendance_end(Request $request)
    {
        try {

            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $buses = Bus::where('attendant_driver_id', $user->id)->first();

            }

            if ($user != null && $user->type == 'admins') {

                $buses = Bus::where('attendant_admins_id', $user->id)->first();

            }

            $school =  School::where('id', $buses->school_id)->first();
            $students = Student::where('bus_id', $buses->id)->where('school_id', $school->id)->where('trip_type', '!=', 'start_day')->orderBy('id', 'desc')
            ->with([
                'schools',
                'gender',
                'religion',
                'bus',
                'grade',
                'classroom',
                'my_Parents',
                'attendant_admins',
                'attendant_driver',
                'attendance',
                'absences',
            ])
            ->get();
            $type = 'end';
            $trip = Trip::where('bus_id', $buses->id)->where('trips_date', date('Y-m-d'))->where('trip_type', $type)->where('status', 0)->first();
            $attendance = Attendance::where('type', 'end')->where('bus_id', $buses->id)->where('attendence_date', date('Y-m-d'))->get();
            $stids = $students->pluck('id');

            if ($user != null && $buses != null && $trip != null) {
                return response()->json([
                    'data' => [
                        'school' => $school,
                        'students' => $students,
                        'buses' => $buses,
                        'trip' => $trip,
                        'attendance' => $attendance,
                        'trip_type' => $type,
                        'trip_students_all_count' => $students->count(),
                        'attendance_trip_students_all_count' => $attendance->whereIn('student_id', $stids)->count(),
                        'attendance_trip_students_presence_count' => $attendance->whereIn('student_id', $stids)->where('attendence_status', true)->count(),
                        'attendance_trip_students_absent_count' => $attendance->whereIn('student_id', $stids)->where('attendence_status', false )->count(),

                    ],
                    'message' => 'success message',
                    'status' => true
                ]);
            }

            return response()->json([
                'message' => 'trip data not found',
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return response()->json([
               'message' => $exception->getMessage(),
               'status' => false
            ], 500);
        }
    }




    public function store_attendences(Request $r)
    {


        try {
            $validator = Validator::make($r->all(), [
                'trip_type'      =>   ["required" , "max:255", "in:end,start"],
                'attendences.*'  =>   ['required', "in:presence,absent"],
                'attendences'    =>   ['required','array',"in:absent,presence"],
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }
            $user = Attendant::where('id', $r->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {

                $buses = Bus::where('attendant_driver_id', $user->id)->first();

            }

            if ($user != null && $user->type == 'admins') {

                $buses = Bus::where('attendant_admins_id', $user->id)->first();

            }
            $school =  School::where('id', $buses->school_id)->first();

            $trip = Trip::where('bus_id', $buses->id)->where('trips_date', date('Y-m-d'))->where('trip_type', $r->trip_type)->first();


            if ($school != null && $trip != null) {
                if ($trip->status != 0) {

                    return response()->json(['errors' => true, 'message' => 'لا يمكن اضافة غياب بعد انتهاء الرحلة'], 500);

                }
                if ($r->attendences != null) {
                    foreach ($r->attendences as $studentid => $attendence) {

                        if( $attendence == 'presence' ) {
                            $attendence_status = true;
                        } else if( $attendence == 'absent' ){
                            $attendence_status = false;
                        }
                        $student = Student::where('id', $studentid)->first();

                        if ($student == null) {
                            return response()->json(['errors' => true, 'message' => 'student not found'], 500);

                        }

                        if (isset($student->attendance()->where('type', $r->trip_type)->where('attendence_date',date('Y-m-d'))->first()->student_id)) {
                            return response()->json(['errors' => true, 'message' => 'تم اضافة غياب الطالب من قابل'], 500);
                        }
                        if ($trip != null) {
                            Attendance::create([
                                'student_id'=> $studentid,
                                'grade_id'=> $student->grade_id,
                                'classroom_id'=> $student->classroom_id,
                                'attendence_date'=> date('Y-m-d'),
                                'school_id' => $student->school_id,
                                'bus_id' => $student->bus_id,
                                'attendant_driver_id' => $student->attendant_driver_id,
                                'attendant_admins_id' => $student->attendant_admins_id,
                                'attendence_status'=> $attendence_status,
                                'type'=> $r->trip_type,
                                'trip_id' =>  $trip->id,
                            ]);


                        }

                        if ($attendence == 'presence') {


                            $parent_ids = DB::table('my__parent_student')->where('student_id', $student->id)->pluck('my__parent_id');

                            $parents = My_Parent::whereIn('id', $parent_ids)->orderBy('id', 'desc')->get();


                            if ($user  != null && $parents->count() > 0) {
                                foreach($parents as $parent) {
                                $message = AttendantParentMessage::create([

                                    'static_message_id' => 2,
                                    'my__parent_id' => $parent->id,
                                    'attendant_id'  => $user->id,
                                    'student_id'     => $student->id,

                                ]);
                                }
                        }



                        }

                        if ($attendence == 'absent') {


                            $parent_ids = DB::table('my__parent_student')->where('student_id', $student->id)->pluck('my__parent_id');

                            $parents = My_Parent::whereIn('id', $parent_ids)->orderBy('id', 'desc')->get();


                            if ($user  != null && $parents->count() > 0) {
                                foreach($parents as $parent) {
                                $message = AttendantParentMessage::create([

                                    'static_message_id' => 7,
                                    'my__parent_id' => $parent->id,
                                    'attendant_id' => $user->id,
                                    'student_id'     => $student->id,

                                ]);
                                }
                        }

                        }

                    }

                    return response()->json(['errors' => false, 'message' => 'attendences added successfully'], 200);



                }

                return response()->json(['errors' => true, 'message' => 'يجب اضافة عنصر واحد علي الاقل'], 500);

            }
            return response()->json(['errors' => true, 'message' => 'Something was wrong'], 500);
        } catch (\Exception $exception) {
            return response()->json([
               'message' => $exception->getMessage(),
               'status' => false
            ], 500);
        }
    }


    public function off(Request $r, $id)
    {
        $user = Attendant::where('id', $r->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        $trip = Trip::where('bus_id', $buses->id)->where('id', $id)->first();
        if($trip->trips_date == date('Y-m-d')) {

            if ($trip->status == 0) {
                $students_attendence_ids = $trip->attendances->pluck('student_id');
                $students_not_attendence = Student::where('bus_id', $trip->bus_id)
                ->whereIn('trip_type', attendence_absence_type($trip->trip_type))
                ->whereNotIn('id', $students_attendence_ids)
                ->get();

                if($students_not_attendence->isNotEmpty()) {

                    foreach ($students_not_attendence as $not_attendence) {
                        Attendance::create([
                            'student_id'=> $not_attendence->id,
                            'attendence_date'=> date('Y-m-d'),
                            'school_id' => $trip->school_id,
                            'attendence_status'=> 0,
                            'trip_id' =>  $trip->id,
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

                if($students_presence->isNotEmpty()) {

                    foreach ($students_presence as $student_presence) {
                            $student_presence->update([
                                'attendence_status' => $attendence_status,

                            ]);

                    }
                }


                if ($trip->trip_type == 'start') {
                    $students_at_school= Attendance::where('trip_id', $trip->id)->where('attendence_status', 1)->get();
                    if($students_at_school->isNotEmpty()) {

                        foreach ($students_at_school as $students_at_school) {
                                $students_at_school->update([
                                    'attendence_status' => 1,

                                ]);

                        }
                    }
                }
                $trip->status = 1;

            }


            $trip->save();

            return response()->json(['errors' => false, 'message' => 'data save successfully'], 200);

        }
        return response()->json(['errors' => true, 'لا تستطيع التعديل علي بيانات الرحلة بعد انتهاء اليوم'], 500);

    }


    public function getTripOnMap(Request $request, $id)
    {
        try {
        $user = Attendant::where('id', $request->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }


        if ($user != null && $buses != null) {

            $trip = Trip::where('bus_id', $buses->id)->where('id', $id)->first();

            if($trip != null) {
                if($trip->status == 0 && $trip->trips_date = date('Y-m-d')) {

                    $latitude = $trip->latitude;
                    $longitude = $trip->longitude;
                    return response()->json([
                        'data' => [
                        'latitude' =>   $latitude,
                        'longitude' =>   $longitude,
                        ],
                        'message' => 'success message',
                        'status' => true
                    ]);
            }

            }

        }
        return response()->json([
            'message' => 'trip not found',
            'status' => false,
        ], 404);

    } catch (\Exception $exception) {
        return response()->json([
           'message' => $exception->getMessage(),
           'status' => false
        ], 500);
    }
    }

    public function TripUpdateLocation(Request $request, $id)
    {
        try {

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
        }


        $user = Attendant::where('id', $request->user()->id)->first();

        if ($user != null && $user->type == 'drivers') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }

        if ($user != null && $user->type == 'admins') {

            $buses = Bus::where('id', $user->bus_id)->first();

        }


        if ($user != null && $buses != null) {

            $trip = Trip::where('bus_id', $buses->id)->where('id', $id)->first();

            if($trip != null) {

                if($trip->status == 0 && $trip->trips_date = date('Y-m-d')) {

                    $trip->latitude =  $request->latitude;
                    $trip->longitude = $request->longitude;
                    $trip->save();
                    event(new MyEvent($trip->longitude ,$trip->latitude));

                    return response()->json([
                        'message' => 'success message',
                        'status' => true
                    ]);
            }

            }

        }
        return response()->json([
            'message' => 'trip not found',
            'status' => false,
        ], 404);

    } catch (\Exception $exception) {
        return response()->json([
           'message' => $exception->getMessage(),
           'status' => false
        ], 500);
    }
    }



}
