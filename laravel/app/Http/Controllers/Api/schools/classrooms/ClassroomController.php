<?php

namespace App\Http\Controllers\Api\schools\classrooms;

use App\Http\Controllers\Controller;
use App\Models\{
    School,
    Classroom,
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Validation\Rule,
};

class ClassroomController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = School::where('id', $request->user()->id)->first();

            if ($user != null) {
                $grade_id = isset($request->grade_id) && $request->grade_id != '' ? $request->grade_id : null;

                $classrooms = $user->classrooms()->with(['grade']);
                if ($grade_id != null) {
                    $classrooms = $classrooms->where(function ($q) use ($request) {
                        return $q->when($request->grade_id, function ($query) use ($request) {

                            return $query->where('grade_id', $request->grade_id);
                        });
                    });
                }

                $classrooms = $classrooms->orderBy('id', 'desc')->get();
                return response()->json([
                    'data' => [
                        'classrooms' => $classrooms,
                        'grades' => $user->grades()->get(),

                    ],
                    'message' => __("success message"),
                    'status' => true
                ]);
            }

            return response()->json(['message' => __('Classrooms not found'), 'status' => false,], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function getShow(Request $request, $id)
    {
        try {
            $classroom = Classroom::where('school_id', $request->user()->id)->where('id', $id)
                ->with([
                    'school',
                    'grade',
                    'students',
                ])
                ->first();
            if ($classroom != null) {
                return response()->json([
                    'data' => $classroom,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __('Classrooms not found'),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function create(Request $r)
    {
        $school =  School::where('id', $r->user()->id)->first();
        $grades =  $school->grades()->get();
        $data = [
            'school'   => $school,
            'grades'   => $grades,

        ];

        if ($school != null) {
            return response()->json([
                'data' => $data,
                'message' => __("success message"),
                'status' => true
            ]);
        }

        return response()->json([
            'message' => __( "data not found"),
            'status' => false,
        ], 500);
    }
    public function getStore(Request $request)
    {
        try {

            $user = School::where('id', $request->user()->id)->first();

            if ($user != null) {
                $date = $request->all();

                $validator = Validator::make($date, [
                    'grade_id' => ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($request, $user, $date) {
                        return $query->where('school_id', $user->id)->where('grade_id', $date['grade_id']);
                    })],
                ]);


                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }


                $validator = Validator::make($date, [
                    'name'  => ['required','min:1', 'max:30', Rule::unique('classrooms')->where(function ($query) use ($request, $user, $date) {
                        return $query->where('school_id', $user->id)->where('grade_id', $date['grade_id']);
                    })],
                ]);


                if ($validator->fails()) {
                    return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
                }

                $classroom                  = new Classroom();
                $classroom->name            = $request->name;
                $classroom->school_id       = $user->id;
                $classroom->grade_id        = $date['grade_id'];
                $classroom->save();
                return response()->json(['errors' => false, 'message' => __('Classroom added successfully')], 200);
            }

            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }



    public function edit(Request $r, $id)
    {
        $user = School::where('id', $r->user()->id)->first();
        if ($user != null) {
            $classroom = Classroom::where('school_id', $user->id)->where('id', $id)->first();
            $grades =  $user->grades()->get();
            $data = [
                'school'    => $user,
                'grades'    => $grades,
                'classroom' => $classroom

            ];
            return response()->json([
                'data' => $data,
                'message' => __("success message"),
                'status' => true
            ]);
        }
        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }
    public function update_classroom(Request $request, $id)
    {

        $user = School::where('id', $request->user()->id)->first();
        $classroom = Classroom::where('school_id', $user->id)->where('id', $id)->first();



        if ($user != null && $classroom != null) {
            $date = $request->all();

            $validator = Validator::make($date, [
                'grade_id' => ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($request, $user, $date) {
                    return $query->where('school_id', $user->id)->where('grade_id', $date['grade_id']);
                })],

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 500);
            }

            $validator = Validator::make($date, [
                'name'  => ['required', 'max:255', Rule::unique('classrooms')->where(function ($query) use ($request, $user, $date) {
                    return $query->where('school_id', $user->id)->where('grade_id', $date['grade_id']);
                })->ignore($classroom->id)],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 500);
            }
            $classroom->school_id = $user->id;
            $classroom->grade_id  =  $date['grade_id'];
            $classroom->name      = $request->name;

            $classroom->save();
            return response()->json(['errors' => false, 'message' => 'classroom updated successfully'], 200);
        }
        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }
    public function delete_classroom(Request $request, $id)
    {
        try {

            $user = School::where('id', $request->user()->id)->first();
            $classroom = Classroom::where('school_id', $user->id)->where('id', $id)->first();

            if ($user != null && $classroom != null) {
                $classroom->delete();
                return response()->json(['errors' => false, 'message' => 'classroom deleted successfully'], 200);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function getClassrooms(Request $request, $id)
    {
        $user = School::where('id', $request->user()->id)->first();
        $date = $request->all();
        $date['grade_id'] = $id;

        if ($user != null) {
            $validator = Validator::make($date, [
                'grade_id' => ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($request, $user, $date) {
                    return $query->where('school_id', $user->id)->where('grade_id', $date['grade_id']);
                })],

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'message' => $validator->errors()], 500);
            }

            $classrooms =  Classroom::where('school_id', $user->id)->where("grade_id", $date['grade_id'])->get();

            $data = [
                'classrooms'   => $classrooms,


            ];

            if ($user != null && $classrooms->count() > 0) {
                return response()->json([
                    'data' => $data,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }

            return response()->json([
                'message' => __( "data not found"),
                'status' => false,
            ], 500);
        }

        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
    }
}
