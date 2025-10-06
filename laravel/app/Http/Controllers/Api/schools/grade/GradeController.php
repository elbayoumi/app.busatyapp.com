<?php

namespace App\Http\Controllers\Api\schools\grade;

use App\Http\Controllers\Controller;
use App\Models\{
    School,
    Grade,
    Classroom,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Schools\Grade\GradeResource;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function getAll(Request $request)
    {
        try {


            $grades = Grade::orderBy('order', 'asc')->get();
            if ($grades->count() > 0) {
                return JSON($grades);
            }
            return JSONerror(__("Grades not found"));
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }



    public function getSchoolGrade(Request $request)
    {
        try {

            $grades = School::findOrfail($request->user()->id)->grades()->get();

            if ($grades->count() > 0) {
                return JSON(GradeResource::collection($grades));
            }
            return JSONerror(__("Grades not found"));
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function getShow(Request $request, $id)
    {
        try {

            $user = School::where('id', $request->user()->id)->first();
            $school_grade_id = DB::table('school_grade')->where('school_id', $user->id)->pluck('grade_id');
            $grade = Grade::whereIn('id', $school_grade_id)->where('id', $id)->first();

            if ($grade != null) {
                return response()->json([
                    'data' => GradeResource::make($grade),
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Grades not found"),
                'status' => false,
            ], 400);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function getStore(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'grade_id' =>  'required|array',

            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 200);
            }

            $School = School::find($request->user()->id);


            if ($School != null) {

                foreach ($request->grade_id as $grades) {
                    $grade = Grade::where('id', $grades)->first();
                    if ($grade == null) {

                        return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
                    }
                }

                $School->grades()->sync($request->grade_id);

                return response()->json(['errors' => false, 'message' => __('Grade selected successfully')], 200);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false
            ], 500);
        }
    }


    public function delete_grade(Request $request, $id)
    {

        try {
            $user = School::where('id', $request->user()->id)->first();
            $date['grade_id'] = $id;
            $date['school_id'] = $user->id;

            $validator = Validator::make($date, [
                'school_id' => ['required', 'exists:schools,id'],
                'grade_id' => ['required', 'exists:grades,id', Rule::exists('school_grade')->where(function ($query) use ($request, $user, $id) {
                    return $query->where('school_id', $user->id)->where('grade_id', $id);
                })],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 500);
            }

            $classrooms = Classroom::where('school_id', $user->id)->where('grade_id', $date['grade_id'])->get();

            if ($classrooms->isNotEmpty()) {
                foreach ($classrooms as $classroom) {
                    $classroom->delete();
                }
            }

            $school_grade = DB::table('school_grade')->where('school_id', $user->id)->where('grade_id', $id);


            if ($school_grade->count() > 0) {

                $school_grade->delete();

                return response()->json(['errors' => false, 'message' => __('Grade deleted successfully')], 200);
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
