<?php

namespace App\Http\Controllers\Api\schools\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\Schools\Classrooms\ClassroomsShowResource;
use App\Http\Resources\Schools\Grade\GradeResource;
use App\Http\Resources\Schools\SchoolsResource;
use App\Models\{
    Address,
    Attendant,
    FixedTrip,
    School,
    Classroom,
    Gender,
    Grade,
    My_Parent,
    Religion,
    Student,
    Trip,
    Type_Blood,

};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\Builder\Stub;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{

    public function getSchool(Request $request)
    {
        try {

            $School = School::findOrfail($request->user()->id);

            if ($School->count() > 0) {
                return response()->json([
                    'data' =>  SchoolsResource::make($School),
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('School not found'),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getGrades(Request $request)
    {
        try {

            $grades = School::findOrfail($request->user()->id)->grades()->orderBy('id', 'DESC')->get();

            if ($grades->count() > 0) {
                return response()->json([
                    'data' => GradeResource::collection($grades),
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Grades not found'),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function getGrade(Request $request, $id)
    {
        try {

            $grade = Grade::findOrfail($id);

            if ($grade->count() > 0) {
                return response()->json([
                    'data' => GradeResource::make($grade),
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Grades not found'),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getClassrooms(Request $request)
    {
        try {

            // $classrooms = Classroom::where('school_id', $request->user()->id)
            //     ->orderBy('id', 'desc')
            //     ->get();
            $classrooms =$request->user()->classrooms()->paginateLimit();

            if ($classrooms->count() > 0) {
                return response()->json([
                    'data' => [
                        'classrooms' => ClassroomsShowResource::collection($classrooms),
                        'classrooms_count' => $classrooms->count(),
                        'school_name' => $request->user()->name,

                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Classrooms not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getClassroom(Request $request, $id)
    {
        try {

            $classroom = Classroom::where('id', $id)->where('school_id', $request->user()->id)
                ->with([
                    'school',
                    'grade',
                    'students'
                ])
                ->first();

            if ($classroom != null) {
                return response()->json([
                    'data' => ClassroomsShowResource::make($classroom),

                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Classrooms not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getAttendants(Request $request)
    {
        try {

            $attendants = Attendant::where('school_id', $request->user()->id)
                ->with(['attendant_admins', 'attendant_driver', 'schools', 'gender', 'religion', 'typeBlood', 'bus_admins', 'bus_driver'])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($attendants->count() > 0) {
                return response()->json([
                    'data' => [
                        'attendants' =>  $attendants,
                        'attendant_driver_count' =>  $attendants->where('type', 'drivers')->count(),
                        'attendant_admins_count' =>  $attendants->where('type', 'admins')->count(),
                        'attendants_count' =>  $attendants->count(),
                        'attendants_school_name' =>  $request->user()->name,


                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Attendants not found')
                ,
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function getAttendant(Request $request, $id)
    {
        try {

            $attendant = Attendant::where('id', $id)
                ->with([
                    'attendant_admins',
                    'attendant_driver',
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus_admins',
                    'bus_driver'
                ])
                ->first();

            if ($attendant != null) {
                return response()->json([
                    'data' => $attendant,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Attendants not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getStudents(Request $request)
    {
        try {

            $students = Student::where('school_id', $request->user()->id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendant_admins',
                    'attendant_driver',
                    'attendance',
                    'absences',
                    'bus'
                ])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($students->count() > 0) {
                return response()->json([
                    'data' => $students,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Student not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }






    public function getStudent(Request $request, $id)
    {
        try {

            $student = Student::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendant_admins',
                    'attendant_driver',
                    'attendance',
                    'absences',
                    'bus'
                ])
                ->first();
            if ($student != null) {
                return response()->json([
                    'data' => $student,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Student not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getParents(Request $request)
    {
        try {

            $students_id = Student::where('school_id', $request->user()->id)->pluck('id');
            $ids = DB::table('my__parent_student')->whereIn('student_id', $students_id)->pluck('my__parent_id');
            $parents = My_Parent::whereIn('id', $ids)->with(['students'])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($parents->count() > 0) {
                return response()->json([
                    'data' => $parents,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("parents not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getParent(Request $request, $id)
    {
        try {
            $students_id = Student::where('school_id', $request->user()->id)->pluck('id');
            $ids = DB::table('my__parent_student')->whereIn('student_id', $students_id)->pluck('my__parent_id');
            $student = Student::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendant_admins',
                    'attendant_driver',
                    'attendance',
                    'absences',
                    'bus'
                ])
                ->first();
            $parent = My_Parent::whereIn('id', $ids)->where('id', $id)
                ->with([
                    'students.bus:id,name',
                ])
                ->first();

            if ($parent) {
                return response()->json([
                    'data' => $parent,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("parents not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }



    public function getBuses(Request $request)
    {
        try {

            $buses = School::findOrfail($request->user()->id)->buses()
                ->with([
                    'schools',
                    'students',
                    'attendant_admins',
                    'attendant_driver',
                ])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($buses->count() > 0) {
                return response()->json([
                    'data' => $buses,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("buses not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getBus(Request $request, $id)
    {
        try {
            $bus = $request->user()->grade_parents_byBusId($id);


            return sendJSON($bus);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }



    public function getAddresses(Request $request)
    {
        try {

            $Addresses = Address::where('school_id', $request->user()->id)
                ->with([
                    'schools',
                    'students',
                    'parent',
                    'bus',
                    'grade',
                    'classroom',
                    'old_bus',
                    'old_bus',
                ])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($Addresses->count() > 0) {
                return response()->json([
                    'data' => $Addresses,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Order addresses not found')                ,
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function getAddress(Request $request, $id)
    {
        try {

            $Address = Address::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'schools',
                    'students',
                    'parent',
                    'bus',
                    'grade',
                    'classroom',
                    'old_bus',
                    'old_bus',

                ]);
            return sendJSON($Address);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getReligion(Request $request)
    {
        try {

            $Religion = Religion::get();

            if ($Religion->count() > 0) {
                return JSON($Religion);
            }
            return JSONerror('Religion not found');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function getGender(Request $request)
    {
        try {

            $Gender = Gender::get();

            if ($Gender->count() > 0) {
                return JSON($Gender);
            }
            return JSONerror('Gender not found');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function getType_Blood(Request $request)
    {
        try {

            $Type_Blood = Type_Blood::get();

            if ($Type_Blood->count() > 0) {
                return JSON($Type_Blood);
            }
            return JSONerror('Type Blood not found');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }
    public function updateFixedTrip(Request $request, $estmait = 'start_day')
    {


        try {

            $fixedTrip = FixedTrip::where('school_id', $request->user()->id)->where('type', $estmait)->first();
            $fixedTrip->time_start                = $request->time_start;
            $fixedTrip->time_end                = $request->time_end;
            $fixedTrip->save();
            return JSON($fixedTrip);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }
    public function getFixedTrip(Request $request)
    {
        try {

            $fixedTrip = FixedTrip::where('school_id', $request->user()->id)->get();

            if ($fixedTrip->count() > 0) {
            return JSON($fixedTrip);

            }
            return JSONerror('fixed Trip not found');


        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

    public function trip(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = Validator::make($request->all(), [
                'trip_id' => 'nullable|integer|exists:trips,id',
                'search' => 'nullable|string|max:255',
                'sort_by' => 'nullable|in:id,trip_type,trips_date,created_at',
                'sort_direction' => 'nullable|in:asc,desc',
            ]);

            if ($validated->fails()) {
                return JSONerror($validated->errors()->first(), 422);
            }

            // Query trips for the current school
            $query = Trip::
            where('school_id', $request->user()->id);

            if ($request->filled('trip_id')) {
                $trip = $query->where('id', $request->trip_id)->first();

                if (!$trip) {
                    return JSONerror('Trip not found for this school.', 404);
                }

                return JSON($trip);
            }

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('trip_type', 'like', '%' . $request->search . '%')
                      ->orWhere('trips_date', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('sort_by')) {
                $sortDirection = $request->get('sort_direction', 'desc');
                $query->orderBy($request->sort_by, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $trips = $query->get();

            if ($trips->isEmpty()) {
                return JSONerror('No trips found for this school.', 404);
            }

            return JSON($trips);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    public function routes(Request $request, $trip_id)
    {
        try {
            $trip = Trip::with('routes')
                ->where('id', $trip_id)
                ->where('school_id', $request->user()->id)
                ->first();

            if (!$trip) {
                return JSONerror('No trips found for this school.', 404);
            }

            return JSON($trip);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

}
