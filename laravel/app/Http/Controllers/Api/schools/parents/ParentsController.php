<?php

namespace App\Http\Controllers\Api\schools\parents;

use App\Http\Controllers\Controller;
use App\Models\{
    My_Parent,
};
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function index(Request $request)
    {
        try {

            $parents = My_Parent::where(function ($q) use ($request) {
                return $q->whereHas('students', function ($e) use ($request) {
                    $e->where('school_id', $request->user()->id);
                });
            });
            if (isValidText($request->text)) {
                $parents = $parents->where('name', 'like', "%{$request->text}%");
            }
            $parents = $parents->orderBy('id', 'desc')->paginateLimit();

            return JSON($parents);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $parent = My_Parent::where(function ($q) use ($request) {
                return $q->whereHas(
                    'students',
                    function ($e) use ($request) {
                        return  $e->where('school_id', $request->user()->id);
                    }
                );
            })->where('id', $id)->orderBy('id', 'desc');
            if ($parent->exists()) {
                $parent = $parent->first();
                $children = $parent->students()->where('school_id', $request->user()->id)->with(['grade','bus:id,name', 'my_Parents'])->get();

                return response()->json([
                    'data' => [
                        'parent' => $parent,
                        'children' => $children,

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
    public function indexDosntHaveStudent(Request $request)
    {
        try {

            $parents = My_Parent::where(function ($q) use ($request) {
                return $q->whereDoesntHave('students', function ($e) use ($request) {
                    $e->where('school_id', $request->user()->id);
                });
            });
            if (!empty($request->text)) {
                $parents = $parents->where(function ($q) use ($request) {
                    return $q->when($request->text, function ($query) use ($request) {
                        return $query->where('name', 'like', "%$request->text%");
                    });
                });
            }
            $parents = $parents->orderBy('id', 'desc')->paginateLimit();
            return JSON($parents);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

}
