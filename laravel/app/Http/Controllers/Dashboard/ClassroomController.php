<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{



    public function index(Request $r)
    {

        $school_id = isset($r->school_id) && $r->school_id != '' ? $r->school_id : null;


        $all_classrooms = Classroom::query();
        // dd($text);
        $this->getDataIfNotNull($r->text, $all_classrooms, ['name', 'like', "%$r->text%"]);
        // $all_classrooms = Classroom::query();
        // if ($text != null) {
        //     $all_classrooms = $all_classrooms->where(function ($q) use ($r) {
        //         return $q->when($r->text, function ($query) use ($r) {
        //             return $query->where('name', 'like', "%$r->text%");

        //         });
        //     });
        // }
        $this->getDataIfNotNull($r->school_id, $all_classrooms, ['school_id', $r->school_id]);

        // if ($school_id != null) {
        //     $all_classrooms = $all_classrooms->where(function ($q) use ($r) {
        //         return $q->when($r->school_id, function ($query) use ($r) {
        //             return $query->where('school_id', $r->school_id);

        //         });
        //     });
        // }
        $this->getDataIfNotNull($r->grade_id, $all_classrooms, ['grade_id', $r->grade_id]);

        // if ($grade_id != null) {
        //     $all_classrooms = $all_classrooms->where(function ($q) use ($r) {
        //         return $q->when($r->grade_id, function ($query) use ($r) {

        //             return $query->where('grade_id', $r->grade_id);

        //         });
        //     });
        // }

        $grades = Grade::query();
        if ($school_id != null) {
            $grades = $grades->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    $ids = DB::table('school_grade')->where('school_id', $r->school_id)->pluck('grade_id');
                    return $query->whereIn('id', $ids);
                });
            });
        }
        $grades = $grades->get();



        $all_classrooms = $all_classrooms->orderBy('id', 'desc')->paginate(10);
        // dd($all_classrooms);
        return view('dashboard.classrooms.index', [
            'all_classrooms' => $all_classrooms,
            'grades' => $grades,
            'schools'   => School::get(),

        ]);
    }

    public function create($id)
    {

        $School = School::find($id);

        $all_grades =  $School->grades()->get();
        return view('dashboard.classrooms.create', ['all_grades' => $all_grades, 'school' => $School]);
    }

    public function store(Request $r)
    {

        $r->validate([
            'name'         => 'required|max:255',
            'school_id'    => ['required'],
            'grade_id'     => ['required'],
            'status'       => ['required'],

        ]);
        $class                  = new Classroom();
        $class->name            = $r->name;
        $class->school_id       = $r->school_id;
        $class->grade_id        = $r->grade_id;
        $class->status          = $r->status;
        $class->save();

        return redirect()->back()->with('success', ' تم اضافة صف بنجاح ');
    }

    public function show($id)
    {
        $school = School::find($id);

        return view('dashboard.classrooms.show', ['school' => $school]);
    }
    public function edit($id)
    {


        $class = Classroom::find($id);
        return view('dashboard.classrooms.edit', ['class' => $class]);
    }

    public function update(Request $r, $id)
    {

        $r->validate([
            'name'         => 'required|max:255',
            'status'       => ['required'],

        ]);
        $class = Classroom::find($id);
        if (!empty($r->school_id )) {
            $class->school_id = $r->school_id;
        }

        if (!empty($r->grade_id )) {
            $class->grade_id = $r->grade_id;
        }

        $class->name            = $r->name;
        $class->status          = $r->status;
        $class->save();

        return redirect()->route('dashboard.classrooms.index')->with('success', ' تم اضافة صف بنجاح ');
    }
    public function destroy($id)
    {

        $class  = Classroom::find($id);
        $class->delete();
        return redirect()->back()->with('success', 'تم حذف بيانات الصف بنجاح');
    }

    public function getClassrooms($id)
    {
        if (request()->ajax()) {

            $id_school = request()->school_id;
            return Classroom::where("school_id", $id_school)->where("grade_id", $id)->pluck("name", "id");
        }

        return false;
    }
    // general function to get data if data not null
    private function getDataIfNotNull($name, $all_classrooms, array $values)
    {
        $name = isset($name) && $name != '' ? $name : null;

        if ($name != null) {
            $all_classrooms = $all_classrooms->where(function ($q) use ($name, $values) {
                return $q->when($name, function ($query) use ($values) {
                    return $query->where(...$values);
                });
            });
        }
    }
}
