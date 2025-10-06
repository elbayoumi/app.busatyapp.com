<?php

namespace App\Http\Controllers\Api\Attendant\Trips;

use App\Enum\TripStatusEnum;
use App\Enum\TripTypebBusEnum;
use App\Enums\TripDayStatus;
use App\Http\Controllers\Controller;

use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Support\Facades\DB,
};

use App\Models\{
    Student,
    Trip,
    Attendance,
    Absence,
    My_Parent,
    NotificationText,
    TripDay,
    TripStudent,
};

use App\Http\Resources\StudentWaitingResource;

use App\Notifications\StudentNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TripsController extends Controller
{


    public function getAll(Request $request)
    {
        try {
            $trips = Trip::with(['attendances.students.grade'])
                ->where('bus_id', $request->user()->bus_id)
                ->orderBy('id', 'desc')->paginateLimit();

            if ($trips != null) {
                return response()->json([
                    'data' => [
                        'trips' => $trips,
                        'trips_count' => $trips->count(),
                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function getStartTripFromDay(Request $request)
    {
        try {
            $trip = Trip::startDay()->with(['attendances.students.grade'])
                ->where('bus_id', $request->user()->bus_id)
                ->today()
                ->orderBy('id', 'desc')->first();

            if ($trip != null) {
                return response()->json([
                    'data' => [
                        'trip' => $trip,
                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json(['status' => false, 'message' => __('Something was wrong')], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function getEndTripFromDay(Request $request)
    {
        try {
            $today = now()->toDateString();
            $trip = Trip::endDay()->with(['attendances.students.grade'])
                ->where('bus_id', $request->user()->bus_id)
                ->whereDate('created_at', $today)
                ->orderBy('id', 'desc')->first();
            if ($trip != null) {
                return response()->json([
                    'data' => [
                        'trip' => $trip,
                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Something was wrong'], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function getShow(Request $request, $id)
    {
        try {
            $trip = Trip::where('id', $id)->where('bus_id', $request->user()->bus_id)
                ->with([
                    'bus',
                    'attendances.students.grade',
                    'school',
                ])
                ->first();
            $absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
                ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();
            return response()->json([
                'data' => [
                    'students_absence_ids'   => $absence_ids,
                    'trip' => $trip,

                ],
                'message' => __("success message"),
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    // public function removeAbsencesToSchool(Request $request)
    // {
    //     try {

    //         $request->validate([
    //             'student_id' => 'required|exists:students,id',
    //         ]);
    //         $current_date = Carbon::now()->format('Y-m-d');

    //         $absence=Absence::where('attendence_date',$current_date)->where('student_id',$request->student_id);
    //         if($absence->exists()){
    //             $student_absence=$absence->first();
    //             if ($student_absence->attendence_type=='full_day'){
    //                 $absence->attendence_type='end_day';
    //                 $absence->save();
    //             }else if($student_absence->attendence_type=='start_day'){
    //                 $absence->delete();
    //             }
    //         }else{
    //             return response()->json(['errors' => true, 'messages' => 'هذا الطالب غير غائب'], 200);

    //         }
    //     } catch (\Exception $exception) {
    //         return response()->json([
    //             'message' => $exception->getMessage(),
    //             'status' => false
    //         ], 500);
    //     }
    // }
    // public function removePresentOnBusToSchool(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'student_id' => ['required|exists:students,id']
    //         ]);
    //         $current_date = Carbon::now()->format('Y-m-d');
    //         $attendance=Attendance::where('student_id', $request->student_id)->where('attendence_date',$current_date)->where('attendence_type','start_day');

    //         if($attendance->exists()) {
    //             $attendance->delete();
    //         }
    //         $absence=Absence::where('attendence_date',$current_date)->where('student_id',$request->student_id);
    //         if($absence->exists()){
    //             $student_absence=$absence->first();
    //             $type=$student_absence->attendence_type;
    //             if (($type=='end_day')||($type=='start_day')){
    //                 $absence->attendence_type='full_day';
    //                 $absence->updated_by=$request->user()->username;
    //                 $absence->save();
    //             }else{
    //                 $student = Student::where('id', $request->student_id)
    //                 ->has('my_Parents')
    //                 ->first();
    //                 $my__parent_id=$student->my_Parents[0]->pivot['my__parent_id'];
    //                 $absence=new Absence();
    //                 $absence->school_id=$request->user()->school_id;
    //                 $absence->bus_id=$request->user()->bus_id;
    //                 $absence->my__parent_id=$my__parent_id;
    //                 $absence->student_id=$request->student_id;
    //                 $absence->attendence_date=$current_date;
    //                 $absence->attendence_type='start_day';
    //                 $absence->created_by=$request->user()->username;
    //                 $absence->save();

    //             }
    //         }else{
    //             return response()->json(['errors' => true, 'messages' => 'هذا الطالب غير غائب'], 200);

    //         }
    //     } catch (\Exception $exception) {
    //         return response()->json([
    //             'message' => $exception->getMessage(),
    //             'status' => false
    //         ], 500);
    //     }
    // }

    public function removePresentOnBusToSchool(Request $request)
    {
        try {
            // $school_id=$request->user()->school_id;
            // $request->validate([
            //     'student_id' => [
            //         'required',
            //         function ($attribute, $value, $fail) use ($school_id) {
            //             // Check if the student exists in the trip table for the authenticated user's bus_id
            //             if (!Attendance::where('student_id', $value)->where('attendence_date',Carbon::now()->format('Y-m-d'))->where('attendance_type','start_day')
            //                 ->exists()) {
            //                 $fail('هذا الطالب غير حاضر');
            //             }
            //         },
            //     ],
            // ]);

            $student_id = $request->student_id;
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->startDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['onboard_at' => null]);

            $current_date = Carbon::now()->format('Y-m-d');
            $attendance = Attendance::where('student_id', $request->student_id)->where('attendence_date', $current_date)->where('attendance_type', 'start_day');

            if ($attendance->exists()) {
                $attendance->delete();
            } else {
                return response()->json(['errors' => true, 'messages' => 'هذا الطالب غير حاضر'], 200);
            }
            // $absence=Absence::where('attendence_date',$current_date)->where('student_id',$request->student_id);
            // if($absence->exists()){
            //     $student_absence=$absence->first();
            //     $type=$student_absence->attendence_type;
            //     if ($type=='full_day'){
            //         $absence->attendence_type='end_day';
            //         $absence->updated_by=$request->user()->username;
            //         $absence->save();
            //     }else if($type=='start_day'){
            //         $absence->delete();

            //     }
            // }else{
            //     return response()->json(['errors' => true, 'messages' => 'هذا الطالب غير غائب'], 200);

            // }
            return response()->json([
                'data' => [
                    'trip' => 'تم النقل الي الانتظار',

                ],
                'message' => __("success message"),
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function removeAbsenceToSchool(Request $request)
    {
        try {
            // $request->validate([
            //     'student_id' => ['required|exists:students,id']
            // ]);
            $student_id = $request->student_id;
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->startDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['status' => 'waiting']);

            $current_date = Carbon::now()->format('Y-m-d');
            $absence = Absence::where('attendence_date', $current_date)->where('student_id', $request->student_id)->whereIn('attendence_type', ['start_day', 'full_day']);
            if ($absence->exists()) {
                $student_absence = $absence->first();
                $type = $student_absence->attendence_type;
                if ($type == 'full_day') {
                    $student_absence->attendence_type = 'end_day';
                    $student_absence->updated_by = $request->user()->username;
                    $student_absence->save();
                } else if ($type == 'start_day') {
                    $student_absence->delete();
                }
            } else {
                return response()->json(['error' => true, 'message' => __('This student is not absent')], 200);
            }
            return response()->json([
                'data' => [
                    'message' => $student_absence,
                    'trip' => __("Moved to waiting"),

                ],
                'message' => __("success message"),
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function removeAbsenceToHome(Request $request, $student_id)
    {
        try {
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->endDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['status' => 'waiting']);

            $current_date = Carbon::now()->format('Y-m-d');
            $trip_id = $this->trip_id($request);
            $removeAbsence = removeAbsence(new Absence, $request, $student_id, 'end_day');
            $attendance = Attendance::where('student_id', $student_id)->where('attendance_type', 'end_day')->where('attendence_date', $current_date)->exists();
            if (!$attendance) {
                $attendance = new Attendance();
                $attendance->school_id = $request->user()->school_id;
                $attendance->student_id = $student_id;
                $attendance->attendence_date = $current_date;
                $attendance->trip_id = $trip_id;
                $attendance->attendence_status = 0;
                $attendance->attendance_type = 'end_day';
                $attendance->save();
            }

            // $on_bus = $this->processArrivedStudentToHomeEndTripFromSchool($request,$student_id, 0);

            return response()->json([
                'data' => [
                    'trip' => $removeAbsence,
                    'attendance' => $attendance,

                ],
                'message' => __("success message"),
                'status' => true
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * Summary of trip_id
     * @param mixed $request
     * @return mixed
     */
    private function trip_id($request)
    {
        $current_date = Carbon::now()->format('Y-m-d');

        $trip_id = Trip::where('bus_id', $request->user()->bus_id)->where('trips_date', $current_date)->where('trip_type', 'end_day')->where('status', 0);
        if ($trip_id->exists()) {
            $trip_id = $trip_id->first();
            return $trip_id->id;
        } else {
            return null;
        }
    }



    public function storeStartTripFromHome(Request $request)
    {
        // ---- Validate request (schema untouched)
        $validator = Validator::make($request->all(), [
            'trip_id'   => ['required', 'exists:trips,id'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'   => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        // ---- Calculate today's date based on app timezone
        // ---- Calculate date (from frontend or fallback to system)
        $date = $request->input('date', Carbon::today()->toDateString());

        try {
            $payload = DB::transaction(function () use ($request, $date) {
                $user = $request->user();

                // Fetch Trip by ID
                $trip = Trip::query()->where('trip_type','start_day')->find($request->trip_id);
                if (! $trip) {
                    return [
                        'http' => 404,
                        'body' => [
                            'status'  => false,
                            'message' => __('Trip not found'),
                        ],
                    ];
                }

                // Lock row to avoid duplicates in concurrent calls
                $tripDay = TripDay::query()
                    ->where('trip_id', $trip->id)
                    ->whereDate('date', $date)
                    ->lockForUpdate()
                    ->first();

                // If CLOSED → block re-opening
                if ($tripDay && $tripDay->status === TripDayStatus::CLOSED) {
                    return [
                        'http' => 409,
                        'body' => [
                            'status'  => false,
                            'message' => __('Trip day is already closed for :date', ['date' => $date]),
                            'data'    => ['trip_day' => $tripDay],
                        ],
                    ];
                }

                // If exists and SCHEDULED → mark as OPEN
                if ($tripDay && $tripDay->status === TripDayStatus::SCHEDULED) {
                    $tripDay->fill([
                        'status'        => TripDayStatus::OPEN,
                        'opened_at'     => now(),
                        'openable_id'   => optional($user)->getKey(),
                        'openable_type' => optional($user)->getMorphClass(),
                        'bus_id'        => $trip->bus_id,
                    ])->save();
                }

                // If not exists → create OPEN TripDay
                if (! $tripDay) {
                    $tripDay = TripDay::create([
                        'trip_id'       => $trip->id,
                        'date'          => $date,
                        'status'        => TripDayStatus::OPEN,
                        'bus_id'        => $trip->bus_id,
                        'opened_at'     => now(),
                        'openable_id'   => optional($user)->getKey(),
                        'openable_type' => optional($user)->getMorphClass(),
                    ]);
                }

                // Attach current user as attendant (prefer TripDay relation, fallback to Trip)
                $attachedOn = null;

                if (method_exists($tripDay, 'attendants')) {
                    $tripDay->attendants()->syncWithoutDetaching([$user->id]);
                    $attachedOn = 'trip_day';
                } elseif (method_exists($trip, 'attendants')) {
                    $trip->attendants()->syncWithoutDetaching([$user->id]);
                    $attachedOn = 'trip';
                } else {
                    return [
                        'http' => 422,
                        'body' => [
                            'status'  => false,
                            'message' => __('No attendants relation found on TripDay or Trip. Define a belongsToMany relation to proceed.'),
                            'data'    => ['trip_day' => $tripDay],
                        ],
                    ];
                }

                return [
                    'http' => 200,
                    'body' => [
                        'status'  => true,
                        'message' => __('Trip day ready and attendant joined successfully for :date', ['date' => $date]),
                        'data'    => [
                            'trip_day'    => $tripDay->fresh(),
                            'attached_on' => $attachedOn, // "trip_day" or "trip"
                        ],
                    ],
                ];
            });

            return response()->json($payload['body'], $payload['http']);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function arrivedToSchoolStartTripFromHome(Request $request)
    {
        try {
            $request->validate([
                'trip_id' => 'required|exists:trips,id',

            ]);
            $trip = Trip::whereId($request->trip_id)->startDay()->notCompleted()->first();
            $attendant = Attendance::where('trip_id', $request->trip_id)->where('attendence_status', 0)->update([
                'attendence_status' => 1
            ]);

            if ($trip) {

                $trip->status = TripStatusEnum::COMPLETED; // Assign the enum directly
                $trip->end_at = Carbon::now();
                $trip->save();
                return response()->json([
                    'data' => [
                        'trip' => $trip,

                    ],
                    'message' => __("success message"),
                    'status' => true
                ], 200);
            } else {
                return response()->json(['errors' => true, 'message' => __('Cannot close a closed trip')], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function storeEndTripFromSchool(Request $request)
    {
        // ---- Validate request (schema untouched)
        $validator = Validator::make($request->all(), [
            'trip_id'   => ['required', 'exists:trips,id'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'   => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        // ---- Calculate today's date based on app timezone
        // ---- Calculate date (from frontend or fallback to system)
        $date = $request->input('date', Carbon::today()->toDateString());

        try {
            $payload = DB::transaction(function () use ($request, $date) {
                $user = $request->user();

                // Fetch Trip by ID
                $trip = Trip::query()->where('trip_type','end_day')->find($request->trip_id);
                if (! $trip) {
                    return [
                        'http' => 404,
                        'body' => [
                            'status'  => false,
                            'message' => __('Trip not found'),
                        ],
                    ];
                }

                // Lock row to avoid duplicates in concurrent calls
                $tripDay = TripDay::query()
                    ->where('trip_id', $trip->id)
                    ->whereDate('date', $date)
                    ->lockForUpdate()
                    ->first();

                // If CLOSED → block re-opening
                if ($tripDay && $tripDay->status === TripDayStatus::CLOSED) {
                    return [
                        'http' => 409,
                        'body' => [
                            'status'  => false,
                            'message' => __('Trip day is already closed for :date', ['date' => $date]),
                            'data'    => ['trip_day' => $tripDay],
                        ],
                    ];
                }

                // If exists and SCHEDULED → mark as OPEN
                if ($tripDay && $tripDay->status === TripDayStatus::SCHEDULED) {
                    $tripDay->fill([
                        'status'        => TripDayStatus::OPEN,
                        'opened_at'     => now(),
                        'openable_id'   => optional($user)->getKey(),
                        'openable_type' => optional($user)->getMorphClass(),
                        'bus_id'        => $trip->bus_id,
                    ])->save();
                }

                // If not exists → create OPEN TripDay
                if (! $tripDay) {
                    $tripDay = TripDay::create([
                        'trip_id'       => $trip->id,
                        'date'          => $date,
                        'status'        => TripDayStatus::OPEN,
                        'bus_id'        => $trip->bus_id,
                        'opened_at'     => now(),
                        'openable_id'   => optional($user)->getKey(),
                        'openable_type' => optional($user)->getMorphClass(),
                    ]);
                }

                // Attach current user as attendant (prefer TripDay relation, fallback to Trip)
                $attachedOn = null;

                if (method_exists($tripDay, 'attendants')) {
                    $tripDay->attendants()->syncWithoutDetaching([$user->id]);
                    $attachedOn = 'trip_day';
                } elseif (method_exists($trip, 'attendants')) {
                    $trip->attendants()->syncWithoutDetaching([$user->id]);
                    $attachedOn = 'trip';
                } else {
                    return [
                        'http' => 422,
                        'body' => [
                            'status'  => false,
                            'message' => __('No attendants relation found on TripDay or Trip. Define a belongsToMany relation to proceed.'),
                            'data'    => ['trip_day' => $tripDay],
                        ],
                    ];
                }

                return [
                    'http' => 200,
                    'body' => [
                        'status'  => true,
                        'message' => __('Trip day ready and attendant joined successfully for :date', ['date' => $date]),
                        'data'    => [
                            'trip_day'    => $tripDay->fresh(),
                            'attached_on' => $attachedOn, // "trip_day" or "trip"
                        ],
                    ],
                ];
            });

            return response()->json($payload['body'], $payload['http']);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function arrivedStudentToHomeEndTripFromSchool(Request $request, $student_id)
    {
        try {
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->endDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['arrived_at' => now()]);

            // $attendance = Attendance::where('trip_id', $request->trip_id)->where('student_id', $request->student_id)->where('attendence_status', 0);
            // if ($attendance->exists()) {
            //     $attendance->attendence_status = 1;
            //     $attendance->save();
            // }

            return $this->processArrivedStudentToHomeEndTripFromSchool($request, $student_id, 0);
            // switch ($data_con) {
            //     case 1:
            //         return response()->json([
            //             'data' => [
            //                 'message' => 'تم تغير حالة الطالب لوصل الى المنزل',

            //             ],
            //             'message' => __("success message"),
            //             'status' => true
            //         ], 200);
            //     case 0:
            //         return response()->json([
            //             'data' => [
            //                 'message' => 'الطالب في المنزل',
            //             ],
            //             'message' => __("success message"),
            //             'status' => false
            //         ], 200);
            // }
            // return response()->json([
            //     'data' => [
            //         'attendance' => 'هناك خطأ',

            //     ],
            //     'message' => __("success message"),
            //     'status' => false
            // ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function removeArrivedStudentToHomeEndTripFromSchool(Request $request, $student_id)
    {
        try {
            $student = Student::whereId($student_id)->first();
            $trip = Trip::where('bus_id', $student->bus_id)->endDay()->notCompleted()->first();
            TripStudent::where('trip_id', $trip->id)
                ->where('student_id', $student_id)
                ->update(['arrived_at' => null]);

            return $this->processArrivedStudentToHomeEndTripFromSchool($request, $student_id, 1);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * Process arrived student to home end trip from school
     * @param Request $request
     * @param int $student_id
     * @param int $attendence_status
     * @return \Illuminate\Http\JsonResponse
     */
    private function processArrivedStudentToHomeEndTripFromSchool($request, $student_id, $attendence_status)
    {
        if (!$attendence_status) {
            $student = Student::find($student_id);
            $body = "{$student->name} وصل المنزل";


            Notification::send($student, new StudentNotification('مساء الخير', $body));
        }


        $current_date = Carbon::now()->format('Y-m-d');

        $trip_id = Trip::where('bus_id', $request->user()->bus_id)->endDay()->notCompleted();
        if ($trip_id->exists()) {
            $trip_id = $trip_id->first();
            $trip_id = $trip_id->id;
        } else {
            return response()->json([
                'data' => [
                    'message' => __('No trip available now, please open the trip first'),
                ],
                'message' => __("success message"),
                'status' => false
            ], 200);;
        }
        $attendance = Attendance::where('trip_id', $trip_id)->where('student_id', $student_id)->where('attendence_status', $attendence_status);
        if ($attendance->exists()) {

            $attendance->where('attendence_status', $attendence_status)->update([
                'attendence_status' => !$attendence_status

            ]);
            $removeAbsence = removeAbsence(new Absence, $request, $student_id, 'end_day');
            return response()->json([
                'data' => [
                    'message' => __('Student status updated'),
                    'removeAbsence' => $removeAbsence,
                ],
                'message' => __("success message"),
                'status' => false
            ], 200);
        } else {
            $attendance = new Attendance();
            $attendance->attendence_status = !$attendence_status;
            $attendance->trip_id = $trip_id;
            $attendance->student_id = $student_id;
            $attendance->attendence_date = $current_date;
            $attendance->school_id = $request->user()->school_id;
            $attendance->save();
            $removeAbsence = removeAbsence(new Absence, $request, $student_id, 'end_day');
            return response()->json([
                'data' => [
                    'message' => __('Student status updated'),
                    'removeAbsence' => $removeAbsence,
                ],
                'message' => __("success message"),
                'status' => false
            ], 200);
        }
    }
    /**
     * End trip from school to home
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function arrivedToHomeEndTripFromSchool(Request $request)
    {
        try {

            $request->validate([
                'trip_id' => 'required|exists:trips,id',
            ]);
            $current_date = Carbon::now()->format('Y-m-d');
            $trip = Trip::whereId($request->trip_id)->endDay()->notCompleted();
            $attendence_condtion = Attendance::where('trip_id', $request->trip_id)->where('attendence_status', 0)->exists();
            if ($trip->exists() && (!$attendence_condtion)) {
                $trip = $trip->first();
                $trip->status = TripStatusEnum::COMPLETED;
                $trip->end_at = Carbon::now();
                $trip->save();
                return response()->json([
                    'data' => [
                        'trip' => $trip,

                    ],
                    'message' => __("success message"),
                    'status' => true
                ], 200);
            } else {
                return response()->json(['errors' => true, 'message' => __('Cannot close a trip that does not exist or has students')], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    /**
     * Create a new trip.
     *
     * @param  \App\Models\Trip  $trip
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Enums\TripTypebBusEnum  $trip_type
     * @return \App\Models\Trip
     */
    private function tripProcess($trip, $request, TripTypebBusEnum $trip_type)
    {
        $current_date = Carbon::now()->format('Y-m-d');
        $trip->latitude = $request->latitude;
        $trip->longitude = $request->longitude;
        $trip->bus_id = $request->user()->bus_id;
        $trip->school_id = $request->user()->school_id;
        $trip->trips_date = $current_date;
        $trip->trip_type = $trip_type; // Store the enum's string value
        $trip->save();
        $trip->load('creator');
        return $trip;
    }



    public function showCurrentWaitingToSchool(Request $request)
    {
        try {
            $user = $request->user();

            $trip = Trip::where('bus_id', $user->bus_id)->startDay()->notCompleted()->first();

            if (!$trip) {
                return response()->json([
                    'waiting' => [],
                    'message' => "No active trip found",
                    'status' => false,
                ], 200);
            }

            $waiting = $trip->students()
                ->wherePivot('status', 'waiting')
                ->with(['temporaryAddresses', 'my__parents'])
                ->get();

            return response()->json([
                'waiting' => StudentWaitingResource::collection($waiting),
                'message' => __("success message"),
                'status' => true,
            ]);
        } catch (\Exception $exception) {
            Log::error('showCurrentWaitingToSchool error: ' . $exception->getMessage());
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false,
            ], 500);
        }
    }



    /**
     * Present all students on bus at end day
     *
     * @param Request $request
     * @param int $trip_id
     * @return \Illuminate\Http\JsonResponse
     */
    private function present_all_on_bus_end_day($request, $trip_id)
    {


        try {
            // Start the transaction
            DB::beginTransaction();
            $attendence_date = Carbon::now()->format('Y-m-d');

            $waitings = Student::where('bus_id', $request->user()->bus_id)->waiting('end_day')->get();
            $attendanceRecords = [];

            foreach ($waitings as $waiting) {
                $attendanceRecords[] = [
                    'school_id' => $waiting->school_id,
                    'student_id' => $waiting->id,
                    'attendence_date' => $attendence_date,
                    'trip_id' => $trip_id,
                    'attendence_status' => 0,
                    'attendance_type' => 'end_day',

                ];
            }

            // Batch insert the attendance records
            Attendance::insert($attendanceRecords);

            // Commit the transaction
            DB::commit();
            return $waitings;
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error or handle it as necessary
            Log::error('Failed to create attendance records', ['error' => $e->getMessage()]);
        }
    }
    // private function waitingProcessQuery($request, $attendence_type, $attendence_status)
    // {
    //     $attendence_date = Carbon::now()->format('Y-m-d');

    //     return Student::select('id', 'name', 'longitude', 'latitude', 'trip_type', 'school_id', 'bus_id')->with('my_Parents')->where('bus_id', $request->user()->bus_id)->whereIn('trip_type', [$attendence_type, 'full_day'])->whereDoesntHave('absences', function ($query) use ($attendence_type, $attendence_date) {
    //         $query->where('attendence_date', $attendence_date)->whereIn('attendence_type', ['full_day', $attendence_type]);
    //     })->whereDoesntHave('attendance', function ($query) use ($attendence_type, $attendence_status, $attendence_date) {
    //         $query->where('attendence_date', $attendence_date)->whereIn('attendance_type', ['full_day', $attendence_type])->where('attendence_status', $attendence_status);
    //     })->get();
    // }
    public function showCurrentPresentOnBusToHome(Request $request, $trip_id)
    {
        try {
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
    public function showCurrentArrivedToHome(Request $request)
    {
        try {

            return response()->json([
                'arrived' => $this->process($request, 'end_day', 1),
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
    public function showCurrentPresentOnBusToSchool(Request $request)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');

            $present_on_bus = Student::where('bus_id', $request->user()->bus_id)
                ->whereHas('attendance', function ($q) use ($current_date) {
                    //0 is on bus and 1 arrived to school
                    $q->where('attendence_date', $current_date)
                        ->where('attendance_type', 'start_day')
                        ->where('attendence_status', 0);
                })
                ->with([
                    'temporaryAddresses',
                    'my__parents'

                ])
                ->orderBy('id', 'desc')
                ->get();
            $trip = Trip::where("bus_id", $request->user()->bus_id)->startDay()->notCompleted();
            return response()->json([
                'present_on_bus' =>  StudentWaitingResource::collection($present_on_bus),
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
    private function process($request, $attendance_type, $attendence_status)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');
            $trip_id = Trip::where('bus_id', $request->user()->bus_id)->where('trip_type', TripTypebBusEnum::from($attendance_type)->value)->notCompleted();
            if ($trip_id->exists()) {
                $trip_id = $trip_id->first();
                $trip_id = $trip_id->id;
            }

            return Student::where('bus_id', $request->user()->bus_id)
                ->whereHas('attendance', function ($q) use ($current_date, $attendance_type, $attendence_status, $trip_id) {
                    //0 is on bus and 1 arrived to school
                    $q->where('attendence_date', $current_date)
                        ->where('attendance_type', $attendance_type)
                        ->where('attendence_status', $attendence_status)
                        ->where('trip_id', $trip_id);
                })
                ->with([
                    'temporaryAddresses' => function ($query) {
                        $query->currentActive();
                    },
                ])
                ->orderBy('id', 'desc')->get();
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function showCurrentAbsencessEndDay(Request $request)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');

            $absences = Student::where('bus_id', $request->user()->bus_id)
                ->whereHas('absences', function ($q) use ($current_date) {
                    $q->where('attendence_date', $current_date)->whereIn('attendence_type', ['end_day', 'full_day']);
                })
                ->with([
                    'temporaryAddresses' => function ($query) {
                        $query->currentActive();
                    },
                ])
                ->orderBy('id', 'desc')->get();

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
    public function statusEndTripFromSchool(Request $request)
    {
        try {
            $user = $request->user();
            $trips = $user->trips()->where('trip_type', 'end_day')->get();

            return JSON($trips);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function notifyList(Request $request)
    {
        try {
            // Fetch notifications based on the criteria
            $notifications = NotificationText::where('for_model_type', Trip::class)
                ->where('to_model_type', My_Parent::class)
                ->whereRaw("JSON_CONTAINS(model_additional, '\"{$request->model_additional}\"', '$.for_model_additional')")
                ->select('id', 'title', 'body', 'default_body', 'group') // Select real columns
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title_tr, // Use the accessor here
                        'body' => $notification->default_body_tr,   // Use the accessor here
                        'group' => $notification->group,
                    ];
                });

            // Partition notifications into two groups based on the 'group' value
            $groupedNotifications = $notifications->partition(function ($notification) {
                return $notification['group'] == 0; // Group 0 condition
            });

            // Group 0 (notifications where 'group' = 0)
            $groupZero = $groupedNotifications[1]->values(); // First collection (group 0)

            // Group 1 (notifications where 'group' = 1)
            $groupOne = $groupedNotifications[0]->values();  // Second collection (group 1)

            // Return both groups in a JSON response
            return JSON([
                'group' => $groupZero,  // Group 1
                'single' => $groupOne,  // Group 0
            ]);
        } catch (\Exception $exception) {
            // Handle any exceptions and return error message
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function statusEndTripFromHome(Request $request)
    {
        try {
            $user = $request->user();
            $trips = $user->trips()->where('trip_type', 'start_day')->get();

            return JSON($trips);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function showCurrentAbsencessStartDay(Request $request)
    {
        try {

            $current_date = Carbon::now()->format('Y-m-d');

            $absences = Student::select('id', 'name', 'bus_id')
                ->where('bus_id', $request->user()->bus_id)
                ->whereHas('absences', function ($q) use ($current_date) {
                    $q->where('attendence_date', $current_date)->where('attendence_type', "<>", 'end_day');
                })
                ->with([
                    'temporaryAddresses' => function ($query) {
                        $query->currentActive();
                    },
                ])
                ->orderBy('id', 'desc')->get();

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
    public function absence(Request $request)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');

            $absences = Student::where('bus_id', $request->user()->bus_id)->whereHas('absences', function ($q) use ($current_date) {
                $q->where('attendence_date', $current_date);
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
}
