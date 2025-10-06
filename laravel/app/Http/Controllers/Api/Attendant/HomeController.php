<?php

namespace App\Http\Controllers\Api\Attendant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Attendant;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Bus;
use App\Models\My_Parent;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function myBus(Request $request)
    {
        try {


            $bus = $request->user()->bus()
                ->with([
                    'schools',
                    'students',
                ])
                ->first();
            return JSON([
                'bus' => $bus,
                'students_count' => $bus->students->count(),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function myStudents(Request $request)
    {
        try {
            $user = $request->user();

                $students = Student::where('bus_id', $user->bus_id)
                    ->with([
                        'schools',
                        'gender',
                        'religion',
                        'typeBlood',
                        'bus',
                        'grade',
                        'classroom',
                        'my_Parents',
                    ])
                    ->orderBy('id', 'desc')->get();

                if ($students->count() > 0) {
                    return response()->json([
                        'data' => [
                            'students' => $students,
                            'students_count' => $students->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }

            // if ($user != null && $user->type == 'drivers') {
            //     $students = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)
            //         ->with([
            //             'schools',
            //             'gender',
            //             'religion',
            //             'typeBlood',
            //             'bus',
            //             'grade',
            //             'classroom',
            //             'my_Parents',
            //         ])
            //         ->orderBy('id', 'desc')->get();

            //     if ($students->count() > 0) {
            //         return response()->json([
            //             'data' => [
            //                 'students' => $students,
            //                 'students_count' => $students->count(),
            //             ],
            //             'message' => __("success message"),
            //             'status' => true
            //         ]);
            //     }
            // }


            return response()->json([
                'message' => __("Student not found"),
                'status' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function myStudentsShow(Request $request, $id)
    {
        try {
            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {
                $students = Student::where('bus_id', $user->bus_id)->where('id', $id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'gender',
                        'religion',
                        'typeBlood',
                        'bus',
                        'grade',
                        'classroom',
                        'my_Parents',

                    ])
                    ->first();


                if ($students != null) {
                    return response()->json([
                        'data' => [
                            'students' => $students,
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }


            if ($user != null && $user->type == 'admins') {
                $students = Student::where('bus_id', $user->bus_id)->where('id', $id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'gender',
                        'religion',
                        'typeBlood',
                        'bus',
                        'grade',
                        'classroom',
                        'my_Parents',

                    ])
                    ->first();


                if ($students != null) {
                    return response()->json([
                        'data' => [
                            'students' => $students,
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }

            return response()->json([
                'message' => __("Student not found"),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function myStudentsParents(Request $request)
    {

        try {
            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'admins') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->pluck('id');

                $ids = DB::table('my__parent_student')->whereIn('student_id', $students_ids)->pluck('my__parent_id');

                $parents = My_Parent::whereIn('id', $ids)
                    ->orderBy('id', 'desc')->paginateLimit();


                if ($parents->count() > 0) {
                    return response()->json([
                        'data' => [
                            'parents' => $parents,
                            'parents_count' => $parents->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }

            if ($user != null && $user->type == 'drivers') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->pluck('id');
                $ids = DB::table('my__parent_student')->whereIn('student_id', $students_ids)->pluck('my__parent_id');

                $parents = My_Parent::whereIn('id', $ids)
                    ->orderBy('id', 'desc')->paginateLimit();


                if ($parents->count() > 0) {
                    return response()->json([
                        'data' => [
                            'parents' => $parents,
                            'parents_count' => $parents->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }




            return response()->json([
                'message' => __('parents not found'),
                'status' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }




    public function myStudentsParentsShow(Request $request, $id)
    {
        try {
            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'admins') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->where('id', $id)->first();


                $ids = DB::table('my__parent_student')->where('student_id', $students_ids->id)->pluck('my__parent_id');

                $parents = My_Parent::whereIn('id', $ids)
                    ->orderBy('id', 'desc')->get();

                if ($parents->count() > 0) {
                    return response()->json([
                        'data' => [
                            'students' => $parents,
                            'parents_count' => $parents->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }


            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'drivers') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->where('id', $id)->first();
                $ids = DB::table('my__parent_student')->where('student_id', $students_ids->id)->pluck('my__parent_id');

                $parents = My_Parent::whereIn('id', $ids)
                    ->orderBy('id', 'desc')->get();

                if ($parents->count() > 0) {
                    return response()->json([
                        'data' => [
                            'students' => $parents,
                            'parents_count' => $parents->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }







            return response()->json([
                'message' => __('parents not found'),
                'status' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function ParentsShow(Request $request, $id)
    {
        try {
            $user = Attendant::where('id', $request->user()->id)->first();

            if ($user != null && $user->type == 'admins') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->pluck('id');
                $parents_id = DB::table('my__parent_student')->whereIn('student_id', $students_ids)->where('my__parent_id', $id)->pluck('my__parent_id');



                if ($parents_id != null) {
                    $parents = My_Parent::whereIn('id', $parents_id)->where('id', $id)
                        ->first();

                    if ($parents != null) {
                        return response()->json([
                            'data' => [
                                'parents' => $parents,
                            ],
                            'message' => __("success message"),
                            'status' => true
                        ]);
                    }
                }
            }


            if ($user != null && $user->type == 'drivers') {
                $students_ids = Student::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)->pluck('id');
                $parents_id = DB::table('my__parent_student')->whereIn('student_id', $students_ids)->where('my__parent_id', $id)->pluck('my__parent_id');



                if ($parents_id != null) {
                    $parents = My_Parent::whereIn('id', $parents_id)->where('id', $id)
                        ->first();

                    if ($parents != null) {
                        return response()->json([
                            'data' => [
                                'parents' => $parents,
                            ],
                            'message' => __("success message"),
                            'status' => true
                        ]);
                    }
                }
            }


            return response()->json([
                'message' => __('parents not found'),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function absencesDeyShow(Request $request)
    {
        try {
            $user = Attendant::where('id', $request->user()->id)->first();


            if ($user != null && $user->type == 'admins') {
                $bus = Bus::where('id', $user->bus_id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'students',
                    ])
                    ->first();
                $absences = Absence::where('bus_id', $bus->id)->where('school_id', $bus->school_id)
                    ->with([
                        'schools',
                        'students',
                        'parent',
                        'bus',
                        'grade',
                        'classroom',
                    ])
                    ->where('attendence_date', date('Y-m-d'))
                    ->orderBy('id', 'desc')->paginateLimit();;


                if ($bus != null) {
                    return response()->json([
                        'data' => [
                            'absences' => $absences,
                            'absences_count' => $absences->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }



            if ($user != null && $user->type == 'drivers') {
                $bus = Bus::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'students',
                    ])
                    ->first();
                $absences = Absence::where('bus_id', $bus->id)->where('school_id', $bus->school_id)
                    ->where('attendence_date', date('Y-m-d'))
                    ->with([
                        'schools',
                        'students',
                        'parent',
                        'bus',
                        'grade',
                        'classroom',
                    ])
                    ->orderBy('id', 'desc')->paginateLimit();;


                if ($bus != null) {
                    return response()->json([
                        'data' => [
                            'absences' => $absences,
                            'absences_count' => $absences->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }




            return response()->json([
                'message' => __('absences not found'),
                'status' => false,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
    public function absencesShow(Request $request, $id)
    {
        try {
            $user = Attendant::where('id', $request->user()->id)->first();


            if ($user != null && $user->type == 'admins') {
                $bus = Bus::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'students',
                    ])
                    ->first();
                $absences = Absence::where('bus_id', $bus->id)->where('school_id', $bus->school_id)->where('id', $id)
                    ->with([
                        'schools',
                        'students',
                        'parent',
                        'bus',
                        'grade',
                        'classroom',
                    ])
                    ->where('attendence_date', date('Y-m-d'))
                    ->orderBy('id', 'desc')->first();;


                if ($bus != null) {
                    if ($absences == null) {
                        return response()->json(['errors' => true, 'message' =>__('No absence request found')], 500);
                    }
                    return response()->json([
                        'data' => [
                            'absences' => $absences,
                            'absences_count' => $absences->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }



            if ($user != null && $user->type == 'drivers') {
                $bus = Bus::where('bus_id', $user->bus_id)->where('school_id', $user->school_id)
                    ->with([
                        'schools',
                        'attendant_admins',
                        'students',
                    ])
                    ->first();
                $absences = Absence::where('bus_id', $bus->id)->where('school_id', $bus->school_id)->where('id', $id)
                    ->where('attendence_date', date('Y-m-d'))
                    ->with([
                        'schools',
                        'students',
                        'parent',
                        'bus',
                        'grade',
                        'classroom',
                    ])
                    ->orderBy('id', 'desc')->first();;


                if ($bus != null) {

                    if ($absences == null) {
                        return response()->json(['errors' => true, 'message' => __('No absence request found')], 500);
                    }
                    return response()->json([
                        'data' => [
                            'absences' => $absences,
                            'absences_count' => $absences->count(),
                        ],
                        'message' => __("success message"),
                        'status' => true
                    ]);
                }
            }




            return response()->json([
                'message' => __('absences not found'),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
}
