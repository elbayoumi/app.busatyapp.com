<?php

namespace App\Http\Controllers\Api\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    AdesSchool,
    Student,
    Ades,
 } ;
use Illuminate\Support\{
    Facades\DB,
};
class AdesController extends Controller
{

    public function index(Request $request)
    {
        try{

            $usr=$request->user();
            $ids = DB::table('my__parent_student')->where('my__parent_id', $usr->id)->pluck('student_id');

            // $student = Student::whereIn('id', $ids)->select('school_id')->toArray();
            $school_ids = Student::whereIn('id', $ids)->pluck('school_id');

            $ades_ids=AdesSchool::whereIn('school_id',$school_ids)->pluck('ades_id');

            // $ades_ids=array_unique($ades_ids);

            $ades=Ades::whereIn('id',$ades_ids)->get();
            return JSON($ades);

        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

}
