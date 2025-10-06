<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Grade;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GradeController extends Controller
{



    public function index(Request $r)
    {



        $all_grade = Grade::query();
        if (!empty($r->school_id)) {
            $all_grade = $all_grade->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    $ids = DB::table('school_grade')->where('school_id', $r->school_id)->pluck('grade_id');
                    return $query->whereIn('id', $ids);

                });
            });
        }
        $all_grade = $all_grade->orderBy('id', 'desc')->paginate(10);

       return view('dashboard.grades.index', [
         'all_grade' => $all_grade,
         'schools' => School::get(),

       ]);
    }


    public function create(){}


    public function store(StoreGradeRequest $r)
    {

    }


    public function show($id)
    {
        $School = School::find($id);

        $all_grade =  $School->grades()->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.grades.index', ['all_grade' => $all_grade]);
    }


    public function edit($id)
    {
        $all_grades = Grade::all();
        $all_schools = School::find($id);

        return view('dashboard.grades.create', ['all_grades' => $all_grades, 'school' => $all_schools]);
    }


    public function update(UpdateGradeRequest $r, $id)
    {

        $School = School::find($id);

       $School->grades()->sync($r->grade_id);

       return redirect()->route('dashboard.schools.index')->with('success', 'تم اضافة المرحلة بنجاح');

    }


    public function destroy(Grade $grade)
    {
        //
    }


    public function getGrades($id)
    {
        $ids = DB::table('school_grade')->where('school_id', $id)->pluck('grade_id');
        return Grade::whereIn('id', $ids)->pluck("name", "id");

    }
}
