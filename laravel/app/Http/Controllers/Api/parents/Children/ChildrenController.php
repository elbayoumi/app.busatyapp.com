<?php

namespace App\Http\Controllers\Api\parents\Children;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadStudentLogoRequest;
use App\Http\Requests\parents\ChildrensStoreRequest;
use App\Models\{
    My__parent_student,
    My_Parent,
    Student,
};
use App\Services\Parent\Address\AddressRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\{
    Facades\DB,
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChildrenController extends Controller
{
    private $addressRequestService;
    public function __construct()
    {
        $this->addressRequestService = new AddressRequestService();
    }
    public function all(Request $request)
    {
        try {
            $text = isset($request->text) && $request->text != '' ? $request->text : null;

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

            ]);
            if ($text != null) {
                $students = $students->where(function ($q) use ($request) {
                    return $q->when($request->text, function ($query) use ($request) {
                        return $query->where('name', 'like', '%' . $request->text . '%');
                    });
                });
            }
            $students = $students->orderBy('id', 'desc')->paginateLimit();


            return response()->json([
                'data' => [
                    'childrens' => $students,
                    'children_count' => $students->count(),
                ],
                'message' => __("success message"),
                'errors' => false
            ]);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }





    public function show(Request $request, $id)
    {
        try {
            // $ids = DB::table('my__parent_student')->where('my__parent_id', $request->user()->id)->pluck('student_id');
            $student = Student::whereId($id)
                ->with([
                    'schools',
                    'gender',
                    'bus',
                    'grade',
                    'classroom',
                ])
                ->first();
            return JSON($student);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function store(ChildrensStoreRequest $request)
    {
        try {
            // Retrieve student_id from the validated request
            $data = $request->validated();
            $student = Student::where('parent_key', $request->parent_key)
                ->where('parent_secret', $request->parent_secret)->first();
            // Get the current parent
            $parent = $request->user();

            $data['student_id'] = $student->id;
            // Associate the student with the parent without detaching existing relationships
            $parent->addchild($student->id);
            // $myParentStudent = My__parent_student::firstOrCreate([
            //     'my__parent_id' => $parent->id,
            //     'student_id' => $student->id,
            // ]);
            $this->addressRequestService->storeChildren($data, $student);

            return JSON(__('Children added successfully'));
        } catch (\Exception $exception) {
            // Handle errors and return a custom JSON response
            return response()->json([
                'errors' => true,
                'message' => 'An error occurred: ' . $exception->getMessage()
            ], 500);
        }
    }


    public function uploadImage(UploadStudentLogoRequest $request)
    {
        $student = Student::findOrFail($request->student_id);

        $student->logo = $request->file('logo');
        $student->save();

        return JSON(__('Image uploaded successfully'), 200);
    }

    public function destroy(Request $request, $id)
    {
        try {
            $ids = My__parent_student::where('my__parent_id', $request->user()->id)
                ->where('student_id', $id);

            if ($ids != null) {
                $ids->delete();
                return JSON(__('child deleted successfully'));
            }
            return JSONerror(__("Something was wrong"));
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }




    public function message(Request $request)
    {
        try {
            // AttendantParentMessag
            My_Parent::where('id', $request->user()->id)->students();
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
