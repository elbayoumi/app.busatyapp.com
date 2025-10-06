<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Models\Attendant;
use Illuminate\Support\Facades\Storage;
use App\Models\Grade;
use App\Models\My_Parent;
use App\Models\Student;
use App\Traits\Schools\CreateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;




class SchoolController extends Controller
{
use CreateTrait;

    public function __construct()
    {
        $this->middleware(['permission:super|schools-list'])->only(['index']);
        $this->middleware(['permission:super|schools-show'])->only(['show']);
        $this->middleware(['permission:super|schools-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|schools-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|schools-destroy'])->only(['destroy']);
    }


    public function gradeSchools(Request $r, $id)
    {

        $ids = DB::table('school_grade')->where('grade_id', $id)->pluck('school_id');
        $all_schools = School::whereIn('id', $ids)->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.schools.index', ['all_schools' => $all_schools]);
    }

    public function index(Request $r, $adesId=null)
    {
        // dd($r->text);
        $text = isset($r->text) && $r->text != '' ? $r->text : null;
        $grade_id = isset($r->grade_id) && $r->grade_id != '' ? $r->grade_id : null;
        //to check if email valid and search
        $email_validate = !(isset($r->email_validate) && $r->email_validate != '') ? "all" : ($r->email_validate == 0 ? null : $r->email_validate);
        $ades_to=$r->ades_to;
        // dd($email_validate);
        $all_schools = $email_validate == "all" ? School::query() : School::query()->where('email_verified_at', $email_validate);
        if ($text != null) {
            $all_schools = $all_schools->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r,) {
                    return $query->where('name', 'like', "%$r->text%")->orWhere('email', 'like', "%{$r->text}%")
                        ->orWhere('phone', 'like', "%{$r->text}%");
                });
            });
        }
        $adesId=(int)$r->adesId>0?(int)$r->adesId:null;

        if ($r->adesId!=null) {
            // dd($r->ades_to);

            $all_schools = $all_schools->whereDoesntHave('adesSchool', function ($q) use ($r) {
                // Add your conditions here based on the 'adesSchool' relationship
                $q->where('ades_id', '=', $r->adesId)->whereIn('to',['all',$r->ades_to]);

            });

        }
        if ($grade_id != null) {
            $all_schools = $all_schools->where(function ($q) use ($r) {
                return $q->when($r->grade_id, function ($query) use ($r) {
                    $ids = DB::table('school_grade')->where('grade_id', $r->grade_id)->pluck('school_id');
                    return $query->whereIn('id', $ids);
                });
            });
        }


        $all_schools = $all_schools->orderBy('id', 'desc')->paginate(10);

            //  => $all_schools,
            $grades = Grade::get();


            // dd($adesId);

        return view('dashboard.schools.index',compact('all_schools','grades','adesId','ades_to') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSchoolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolRequest $r)
    {
        try {
            $School = new School();
            $School->name = $r->name;
            $School->email = $r->email;
            $School->phone = $r->phone;
            $School->password = $r->password;
            $School->address = $r->address;
            $School->city_name = $r->city_name;
            $School->status = $r->status;
            $School->latitude        = $r->latitude;
            $School->longitude           = $r->longitude;
            if ($r->logo) {

                Image::make($r->logo)
                    ->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('uploads/schools_logo/' . $r->logo->hashName()));

                $School->logo = $r->logo->hashName();
            } //end of if



            $School->save();

            $this->processCreateDefaultClassRooms($School);



            return redirect()->route('dashboard.schools.index')->with('success', 'تم الاضافة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        $ids = $school->attendants->where('type', 'admins')->pluck('id');
        $ids_drivers = $school->attendants->where('type', 'drivers')->pluck('id');
        $admins = Attendant::whereIn('id', $ids)->orderBy('id', 'desc')->get();
        $drivers = Attendant::whereIn('id', $ids_drivers)->orderBy('id', 'desc')->get();

        $Student_id = Student::where('school_id', $school->id)->pluck('id');


        $Parent_id = DB::table('my__parent_student')->whereIn('student_id', $Student_id)->pluck('my__parent_id');
        $parents = My_Parent::whereIn('id', $Parent_id)->orderBy('id', 'desc')->get();

        return view('dashboard.schools.show', compact([
            'school',
            'admins',
            'drivers',
            'parents',

        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        return view('dashboard.schools.edit', compact(['school']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSchoolRequest  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSchoolRequest $r, School $school)
    {

        $school->latitude        = $r->latitude;
        $school->longitude           = $r->longitude;
        $school->address = $r->address;
        $school->city_name = $r->city_name;

        if (!empty($r->name)) {
            $school->name = $r->name;
        }
        if (!empty($r->email_verified_at)) {
            $school->email_verified_at = $r->email_verified_at;
        }
        if (!empty($r->email)) {
            $school->email = $r->email;
        }

        if (!empty($r->phone)) {
            $school->phone = $r->phone;
        }

        if (!empty($r->status)) {
            $school->status = $r->status;
        }
        // dd($r->email_verified_at);


        if (!empty($r->password)) {
            $school->password = $r->password;
        }

        if (!empty($r->logo)) {

            if ($school->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/schools_logo/' . $school->logo);
            } //end of if

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/schools_logo/' . $r->logo->hashName()));

            $school->logo  = $r->logo->hashName();
        } //end of if

        $school->save();



        return redirect()->route('dashboard.schools.index')->with('success', 'تم تحديث بيانات المدرسة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {

        if ($school->logo != 'default.png') {

            Storage::disk('public_uploads')->delete('/schools_logo/' . $school->logo);
        } //end of if

        $school->delete();
        return redirect()->back()->with('success', 'تم حذف بيانات المدرسة بنجاح');
    }
}
