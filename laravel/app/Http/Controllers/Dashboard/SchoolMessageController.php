<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\My_Parent;
use App\Models\School;
use App\Models\School_messages;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolMessageController extends Controller
{

    public function index(Request $r)
    {

        $messages = School_messages::query();

        if (!empty($r->school_id)) {
            $messages = $messages->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);

                });
            });
        }




        if (!empty($r->created_at)) {
            $messages = $messages->where(function ($q) use ($r) {
                return $q->when($r->created_at, function ($query) use ($r) {
                    return $query->whereDate('created_at', $r->created_at);

                });
            });
        }

        if (!empty($r->event_date)) {
            $messages = $messages->where(function ($q) use ($r) {
                return $q->when($r->event_date, function ($query) use ($r) {
                    return $query->whereDate('event_date', $r->event_date);

                });
            });
        }

        $messages = $messages->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.school_messages.index', [
            'messages'   => $messages,
            'schools'   => School::get(),

        ]);

    }


    public function create() {

        $school = School::get();


        return view('dashboard.school_messages.create', [
          'school' => $school,
        ]);
    }


    // public function store(Request $request) {

    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'school_id' => ['required'],
    //         'message' => ['required', 'string'],
    //         'event_date'   =>  'required|date|date_format:Y-m-d|after:'.date('Y-m-d'),

    //     ]);


    //     $school = School::where('id', $request->school_id)->first();

    //     $student_ids = Student::where('school_id', $school->id)->pluck('id');
    //     if ($student_ids->count() < 1) {
    //         return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

    //     }
    //     $parent_ids = DB::table('my__parent_student')->whereIn('student_id', $student_ids)->pluck('my__parent_id');
    //     if ($parent_ids->count() < 1) {
    //         return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

    //     }
    //     $parents = My_Parent::whereIn('id', $parent_ids)->orderBy('id', 'desc')->get();

    //     if ($parents->isNotEmpty()) {
    //         foreach($parents as $parent) {

    //             $parent->school_messages()->create([

    //                 'name' => $request->name,
    //                 'school_id' => $school->id,
    //                 'message' => $request->message,
    //                 'event_date' => $request->event_date,

    //             ]);

    //         }

    //     }

    //     return redirect()->route('dashboard.school_messages.index')->with('success', 'تم اضافة البيانات بنجاح');


    // }

    public function store(Request $request) {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'exists:schools,id'],
            'message' => ['required', 'string'],
            'event_date'   =>  'required|date|date_format:Y-m-d|after:'.date('Y-m-d'),

        ]);


        $school = School::where('id', $request->school_id)->first();

        $student_ids = Student::where('school_id', $school->id)->pluck('id');
       if ($student_ids->count() < 1) {
            return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

        }
        $parent_ids = DB::table('my__parent_student')->whereIn('student_id', $student_ids)->pluck('my__parent_id');

        if ($parent_ids->count() < 1) {
            return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

        }
        $parents = My_Parent::whereIn('id', $parent_ids)->orderBy('id', 'desc')->get();

        if ($parent_ids->count() < 1) {
            return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

        }
        $message = School_messages::create([

            'name' => $request->name,
            'school_id' => $school->id,
            'message' => $request->message,
            'event_date' => $request->event_date,

        ]);

        foreach($parents as $parent) {

            $message->parents()->attach($parent);
        }

        return redirect()->route('dashboard.school_messages.index')->with('success', 'تم اضفة البيانات بانجاح');


    }

    public function show($id)
    {
        $message = School_messages::where('id', $id)->first();

        $parents_ids = DB::table('my__parent_school_message')->where('school_messages_id', $message->id)->pluck('my__parent_id');

        $student_ids = DB::table('my__parent_student')->whereIn('my__parent_id', $parents_ids)->pluck('student_id');

        $students = Student::where('school_id', $message->school_id)->whereIn('id', $student_ids)->orderBy('id', 'desc')->paginate(10);

        $students_no = Student::where('school_id', $message->school_id)->whereNotIn('id', $student_ids)->orderBy('id', 'desc')->paginate(10);

        return view('dashboard.school_messages.show', [
            'students' => $students,
            'message' => $message,
            'students_no' => $students_no,

        ]);
    }

    public function destroy($id)
    {
        $School_messages = School_messages::FindOrFail($id);
        $School_messages->delete();
        return redirect()->back()->with('success', 'تم حذف البيانات  بنجاح');
    }
}
