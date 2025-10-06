<?php

namespace App\Http\Controllers\Api\parents\absences;

use App\Http\Controllers\Controller;
use App\Models\{
    Student,
    Trip,
    Absence,
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Support\Facades\DB,
};
use Carbon\Carbon;

class AbsencesController extends Controller
{
    public function index(Request $r)
    {
        try {
            $ids = $r->user()->students->pluck('id')->toArray();

            $text = isset($r->text) && $r->text != '' ? $r->text : null;
            $Absence = Absence::whereIn('student_id', $ids)
                ->with([
                    'students',
                    'bus',
                    'parent',
                ]);

            if ($text != null) {
                $Absence = $Absence->where(function ($q) use ($r) {
                    return $q->when($r->text, function ($query) use ($r) {
                        return $query->whereHas('students', function ($e) use ($r) {
                            $e->where('name', 'like', "%$r->text%");
                        });
                    });
                });
            }
            $Absence = $Absence->orderBy('id', 'desc')
                ->paginateLimit();

            return response()->json([
                'data' => $Absence,
                'message' => __("success message"),
                'errors' => false,
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function create(Request $request)
    {
        try {
            $ids = DB::table('my__parent_student')->where('my__parent_id', $request->user()->id)->pluck('student_id');
            $students = Student::whereIn('id', $ids)->with([
                'schools',
                'gender',
                'religion',
                'typeBlood',
                'bus',
                'grade',
                'classroom',
                'my_Parents',
                'attendance',
                'absences',
            ])
                ->orderBy('id', 'desc')
                ->get();

            if ($students->count() > 0) {
                return response()->json([
                    'data' => [
                        'childrens' => $students,
                        'attendence_type' => [
                            __('full_day'),
                            __('start_day'),
                            __('end_day')
                        ],
                    ],

                    'message' => __("success message"),
                    'errors' => false,
                ]);
            }
            return response()->json([
                'message' => __("children not found"),
                'errors' => true,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }




    // public function store(Request $r, $student_id)
    // {
    //     try {

    //         // $user = My_Parent::where('id', $r->user()->id)->first();
    //         $absence = __('The student is already registered');
    //         $current_date = Carbon::now()->format('Y-m-d');
    //         $data = $r->all();
    //         $data['my__parent_id'] =  $r->user()->id;
    //         $data['student_id'] =  $student_id;
    //         $rules = [
    //             'student_id' => [
    //                 function ($attribute, $value, $fail) use ($student_id, $r) {
    //                     $existsInStudents = Student::whereHas('my__parent_student', function ($q) use ($r, $value) {
    //                         $q->where('my__parent_id', $r->user()->id)
    //                             ->where('student_id', $value);
    //                     }, '>', 0) // إضافة الثالثة المعرفة للتأكد من أن العلاقة موجودة
    //                         ->whereNotNull('bus_id')
    //                         ->exists();

    //                     if (!$existsInStudents) {
    //                         return response()->json(['errors' => true, 'message' => __('The student must be subscribed to the bus')], 500);
    //                     }
    //                 },
    //             ],
    //             'attendence_date' => [
    //                 'required',
    //                 'date',
    //                 'date_format:Y-m-d',
    //                 'after:now',
    //                 function ($attribute, $value, $fail) use ($r) {
    //                     // Convert Arabic numerals to Western numerals
    //                     $value = convertArabicToWesternNumerals($value);

    //                     // Check if the date exists in the database
    //                     $existsInStudents = Absence::where('attendence_date', $value)
    //                         ->whereIn('attendence_type', ['full_day', $r->input('attendence_type')])
    //                         ->exists();

    //                     if ($existsInStudents) {
    //                         $message = __('This student is recorded as absent on the same day with the type of attendance');
    //                         $fail($message);
    //                     }
    //                 },
    //             ],

    //             'attendence_type'   => 'required|in:full_day,end_day,start_day',
    //         ];
    //         $validator = Validator::make($data, $rules);
    //         if ($validator->fails()) {
    //             return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
    //         }
    //         $Student = Student::findOrFail($student_id);




    //         if (in_array($data['attendence_type'], tr_student_check_has_trip_type($Student->trip_type))) {
    //             $absenceStatus = Absence::where('student_id', $student_id)->where('attendence_date', $data['attendence_date']);
    //             $valuePath = [
    //                 'created_by' => 'Parent',

    //                 // 'student_id'         =>    $data['student_id'],
    //                 'attendence_date'    =>  $data['attendence_date'],
    //                 'my__parent_id'      =>    $data['my__parent_id'],
    //                 'school_id'     =>    $Student->school_id,
    //                 'bus_id'        =>    $Student->bus_id,
    //             ];
    //             if ($absenceStatus->where('attendence_type', '<>', $data['attendence_type'])->exists()) {
    //                 $absence = $absenceStatus->update($valuePath + ['attendence_type' =>  'full_day']);
    //             } else {
    //                 $Student->absences()->create($valuePath + ['attendence_type' =>  $data['attendence_type']]);
    //             }

    //             return response()->json([
    //                 'data' => [
    //                     'absence' => $absence,
    //                 ],

    //                 'message' => __("success message"),
    //                 'errors' => false,
    //             ]);
    //         } else {
    //             return response()->json(['errors' => true, 'message' => __('The student must be subscribed to the service')], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //             'status' => false
    //         ], 500);
    //     }
    // }

    public function store(Request $r, $student_id)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');

            $data = $r->all();
            $data['my__parent_id'] = $r->user()->id;
            $data['student_id'] = $student_id;

            $rules = [
                'student_id' => [
                    function ($attribute, $value, $fail) use ($r) {
                        $isLinked = Student::whereHas('my__parent_student', function ($q) use ($r, $value) {
                            $q->where('my__parent_id', $r->user()->id)
                                ->where('student_id', $value);
                        }, '>', 0)
                            ->whereNotNull('bus_id')
                            ->exists();

                        if (!$isLinked) {
                            $fail(__('The student must be subscribed to the bus'));
                        }
                    },
                ],

                'attendence_date' => [
                    'required',
                    'date',
                    'date_format:Y-m-d',
                    'after:now',
                    function ($attribute, $value, $fail) use ($r, $student_id) {
                        $value = convertArabicToWesternNumerals($value); // convert arabic numbers to english

                        $exists = Absence::where('attendence_date', $value)
                            ->where('student_id', $student_id)
                            ->whereIn('attendence_type', ['full_day', $r->input('attendence_type')])
                            ->exists();

                        if ($exists) {
                            $fail(__('This student is recorded as absent on the same day with the type of attendance'));
                        }
                    },
                ],

                'attendence_type' => 'required|in:full_day,end_day,start_day',
            ];

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => true,
                    'messages' => $validator->errors()
                ], 422);
            }

            $Student = Student::findOrFail($student_id);

            if (!in_array($data['attendence_type'], tr_student_check_has_trip_type($Student->trip_type))) {
                return response()->json([
                    'errors' => true,
                    'message' => __('The student must be subscribed to the service')
                ], 403);
            }

            $absenceStatus = Absence::where('student_id', $student_id)
                ->where('attendence_date', $data['attendence_date']);

            $absenceData = [
                'created_by'      => 'Parent',
                'attendence_date' => $data['attendence_date'],
                'my__parent_id'   => $data['my__parent_id'],
                'school_id'       => $Student->school_id,
                'bus_id'          => $Student->bus_id,
            ];

            $absence = null;

            if ($absenceStatus->where('attendence_type', '<>', $data['attendence_type'])->exists()) {
                $absence = $absenceStatus->update($absenceData + ['attendence_type' => 'full_day']);
            } else {
                $absence = $Student->absences()->create($absenceData + ['attendence_type' => $data['attendence_type']]);
            }

            return response()->json([
                'data' => ['absence' => $absence],
                'message' => __('success message'),
                'errors' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }



    public function show(Request $request, $id)
    {
        try {
            $Absence = Absence::whereId($id)
                ->with([
                    'students',
                    'bus',
                    'parent',
                    'bus',
                ])
                ->first();


            if ($Absence != null) {
                return response()->json([
                    'data' => $Absence,
                    'message' => __("success message"),
                    'errors' => false
                ]);
            }
            return response()->json([
                'message' => __("absences not found"),
                'errors' => false,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function delete_absences(Request $request, $id)
    {
        try {
            $ids = $request->user()->students->pluck('id')->toArray();


            $Absence = Absence::whereIn('student_id', $ids)
                ->where('attendence_date', '>', date('Y-m-d'))
                ->where('id', $id)
                ->first();


            if ($Absence != null) {
                $Absence->delete();

                return response()->json(['errors' => false, 'message' => __('Absence deleted successfully')], 200);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function status(Request $request, $student_id, $trip_type = 'start_day')
    {
        try {

            $student = Student::find($student_id);

            $attendence_date = Carbon::now()->format('Y-m-d');
            $trip_cond = Trip::where('bus_id',  $student->bus_id)->where('trips_date', $attendence_date)->where('status', trip_status('work'))->where('trip_type', $trip_type)->exists();
            if (!$trip_cond) {
                return JSONerror($trip_cond . '' . 'Trip for this bus ' . $student->bus_id . ' not open ', 200);
            }
            $trip = Absence::where('student_id', $student_id)->whereIn('attendence_type', [$trip_type, 'full_day'])->where('attendence_date', $attendence_date);
            $trip_condtion = $trip->exists();
            if ($trip_condtion) {
                return JSONerror($trip_condtion . '' . 'student for this bus ' . $student->bus_id . ' is  Absence ', 200);
            }
            return JSON(__("success message"));
        } catch (\Exception $exception) {
            return JSON($exception->getMessage(), 500);
        }
    }
}
