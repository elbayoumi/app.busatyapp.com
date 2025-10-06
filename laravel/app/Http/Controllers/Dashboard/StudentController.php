<?php

namespace App\Http\Controllers\Dashboard;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Models\Absence;
use App\Models\Address;
use App\Models\Attendance;
use App\Models\AttendantParentMessage;
use App\Models\Bus;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\My_Parent;
use App\Models\Religion;
use App\Models\School;
use App\Models\Static_messages;
use App\Models\Student;
use App\Models\Type_Blood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:super|students-list'])->only(['index']);
        $this->middleware(['permission:super|students-show'])->only(['show']);
        $this->middleware(['permission:super|students-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|students-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|students-destroy'])->only(['destroy']);

    }

    public function index(Request $r)
    {

        $all_Student = Student::query();
        if (!empty($r->text)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r) {
                    return $query->where('name', 'like', "%$r->text%");

                });
            });
        }
        if (!empty($r->parent_key)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->parent_key, function ($query) use ($r) {
                    return $query->where('id', $r->parent_key);

                });
            });
        }



        if (!empty($r->school_id)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);

                });
            });
        }
        if (!empty($r->bus_id)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->bus_id, function ($query) use ($r) {
                    return $query->where('bus_id', $r->bus_id);

                });
            });
        }

        if (!empty($r->grade_id)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->grade_id, function ($query) use ($r) {

                    return $query->where('grade_id', $r->grade_id);

                });
            });
        }
        if (!empty($r->classroom_id)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->classroom_id, function ($query) use ($r) {

                    return $query->where('classroom_id', $r->classroom_id);

                });
            });
        }

        if (!empty($r->parent_id)) {
            $all_Student = $all_Student->where(function ($q) use ($r) {
                return $q->when($r->parent_id, function ($query) use ($r) {
                    $ids = DB::table('my__parent_student')->where('my__parent_id', $r->parent_id)->pluck('student_id');
                    return $query->whereIn('id', $ids);

                });
            });
        }


        $all_buses = Bus::query();
        if (!empty($r->school_id)) {
            $all_buses = $all_buses->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);

                });
            });
        }
        $all_buses = $all_buses->get();

        $grades = Grade::query();
        if (!empty($r->school_id)) {
            $grades = $grades->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    $ids = DB::table('school_grade')->where('school_id', $r->school_id)->pluck('grade_id');
                    return $query->whereIn('id', $ids);

                });
            });
        }
        $grades = $grades->get();
        $classrooms = Classroom::query();

        if (!empty($r->school_id)) {
            $classrooms = $classrooms->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);
                });
            });
        }
        if (!empty($r->grade_id)) {
            $classrooms = $classrooms->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('grade_id', $r->grade_id);
                });
            });
        }
        $classrooms = $classrooms->get();
        $parents = My_Parent::get();
        $all_Student = $all_Student->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.students.index', [
        'all_Student' => $all_Student,
        'schools'   => School::get(),
        'all_buses' => $all_buses,
        'grades' => $grades,
        'classrooms' => $classrooms,
        'parents' => $parents,

         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school =  School::get();
        $genders = Gender::get();
        $grades = Grade::get();
        $classrooms = Classroom::get();
        $buses = Bus::get();

        return view('dashboard.students.create', [
            'school'   => $school,
            'genders'   => $genders,
            'grades'   => $grades,
            'classrooms'=> $classrooms,
            'buses' => $buses,


        ]);


    }


    public function store(Request $r)
    {

        $r->validate([
            'name'             => 'required|max:255',
            'phone'            => 'nullable|max:255',
            // 'Date_Birth'       => 'nullable|date|date_format:Y-m-d',
            'grade_id'         =>  'required',
            'gender_id'        =>  'nullable',
            'school_id'        =>  'required',
            // 'type__blood_id'   =>  'nullable',
            'classroom_id'     =>  'required',
            // 'religion_id'      =>  'nullable',
            'address'          =>  'nullable',
            // 'city_name'        => 'nullable|max:255',
            'status'           =>  'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

        ]);


        $student                   = new Student();
        $student->name             = $r->name;
        $student->phone            = $r->phone;
        // $student->Date_Birth       = $r->Date_Birth;
        $student->grade_id         = $r->grade_id;

        $student->gender_id        = $r->gender_id;
        $student->school_id        = $r->school_id;
        $student->classroom_id     = $r->classroom_id;


        $student->address          = $r->address;
        $student->status           = $r->status;
        $student->latitude        = $r->latitude;
        $student->longitude           = $r->longitude;
        $student->save();

        if (!empty($r->bus_id)) {
            if ($r->trip_type == null) {
                return redirect()->back()->withErrors(['error' => 'يجب تحديد نوع الاشتراك في الباص']);

            }

            $buses = Bus::find($r->bus_id);

            if ($buses) {
                $student->trip_type          = $r->trip_type;
                $student->bus_id             = $r->bus_id;
            }
        }
        // dd($student);

        if (!empty($r->address)) {
            // البحث عن السجل بناءً على المعايير
            $address = Address::where('status',1)->where('student_id', $student->id)
                ->where('my__parent_id', $r->user()->id)
                ->first();

            // إذا تم العثور على السجل، قم بتحديث الحقول
            if ($address) {
                // نقل البيانات من الحقل الجديد إلى الحقل القديم
                $address->old_address = $address->address;
                $address->old_latitude = $address->latitude;
                $address->old_longitude = $address->longitude;

                // تحديث الحقول الجديدة
                $address->address = $r->address;
                $address->latitude = $r->latitude;
                $address->longitude = $r->longitude;

                // حفظ التحديثات
                $address->save();
            } else {
                // إذا لم يتم العثور على السجل، قم بإنشاء سجل جديد
                $address = Address::create([
                    'student_id'    => $student->id,
                    'address'   => $r->address,
                    'school_id'     => $student->school_id,
                    'bus_id'        => $student->bus_id,
                    'latitude'      => $r->latitude,
                    'longitude'     => $r->longitude,
                ]);
            }
        }

        if ($r->logo) {

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/students_logo/' . $r->logo->hashName()));

                $student->logo = $r->logo->hashName();

        }//end of if

        $student->save();


        return redirect()->route('dashboard.students.index')->with('success', 'تم اضافة بيانات  الطالب بنجاح');

    }





    public function show($id)
    {
        $student =  Student::find($id);
        return view('dashboard.students.show', [
            'student'   => $student,



        ]);

    }


    public function edit($id)
    {
        $student =  Student::find($id);
        $school =  School::where('id', $student->school_id)->first();
        $genders = Gender::get();
        $typeBlood = Type_Blood::get();
        $religion = Religion::get();
        $grades = $school->grades()->get();
        $grades_ids = $grades->pluck("id");
        $classrooms = Classroom::whereIn('grade_id', $grades_ids)->where('school_id', $school->id)->get();
        $buses = Bus::get();

        return view('dashboard.students.edit', [
            'student'   => $student,
            'school'    => $school,
            'genders'   => $genders,
            'typeBlood' => $typeBlood,
            'religion'  => $religion,
            'grades'    => $grades,
            'classrooms'=> $classrooms,
            'buses'     => $buses,


        ]);

    }


    public function update(Request $r, $id)
    {

        $r->validate([
            'name'             => 'required|max:255',
            'phone'             => 'nullable|max:255',
            // 'Date_Birth'       => 'nullable|date|date_format:Y-m-d',
            'grade_id'        =>  'required',
            'gender_id'        =>  'nullable',
            // 'type__blood_id'   =>  'nullable',
            'classroom_id'        =>  'required',
            // 'religion_id'      =>  'nullable',
            'address'   =>  'required',
            // 'city_name'          => 'nullable|max:255',
            'status'           =>  'required',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

        ]);

        $student = Student::find($id);
        $student->name             = $r->name;
        $student->phone            = $r->phone;
        // $student->Date_Birth       = $r->Date_Birth;
        $student->grade_id         = $r->grade_id;
        $student->gender_id        = $r->gender_id;
        // $student->type__blood_id   = $r->type__blood_id;
        $student->classroom_id     = $r->classroom_id;
        // $student->religion_id      = $r->religion_id;
        $student->address          = $r->address;
        // $student->city_name        = $r->city_name;
                $student->latitude        = $r->latitude;
        $student->longitude           = $r->longitude;
        $student->status           = $r->status;

        if (!empty($r->address)) {
            // البحث عن السجل بناءً على المعايير
            $address = Address::where('status',1)->where('student_id', $student->id)
                ->where('my__parent_id', $r->user()->id)
                ->first();

            // إذا تم العثور على السجل، قم بتحديث الحقول
            if ($address) {
                // نقل البيانات من الحقل الجديد إلى الحقل القديم
                $address->old_address = $address->address;
                $address->old_latitude = $address->latitude;
                $address->old_longitude = $address->longitude;

                // تحديث الحقول الجديدة
                $address->address = $r->address;
                $address->latitude = $r->latitude;
                $address->longitude = $r->longitude;

                // حفظ التحديثات
                $address->save();
            } else {
                // إذا لم يتم العثور على السجل، قم بإنشاء سجل جديد
                $address = Address::create([
                    'student_id'    => $student->id,
                    'address'   => $r->address,
                    'school_id'     => $student->school_id,
                    'bus_id'        => $student->bus_id,
                    'latitude'      => $r->latitude,
                    'longitude'     => $r->longitude,
                ]);
            }
        }

        if ( !empty($r->bus_id)) {
            if ($r->trip_type == null) {
                return redirect()->back()->withErrors(['error' => 'يجب تحديد نوع الاشتراك في الباص']);

            }
            $buses = Bus::find($r->bus_id);

            if ($buses) {
                $student->bus_id                          = $r->bus_id;
                $student->trip_type                       = $r->trip_type;
            }
        }else {

                $student->bus_id                          = null;
                $student->trip_type                       = null;

        }

        if ($r->logo) {

            if ($student->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/students_logo/' . $student->logo);

            }//end of if

            Image::make($r->logo)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/students_logo/' . $r->logo->hashName()));

                $student->logo  = $r->logo->hashName();

        }//end of if

        $student->save();


        return redirect()->route('dashboard.students.index')->with('success', 'تم  تعديل بيانات  الطالب بنجاح');

    }


    public function destroy($id)
    {

        $student = Student::where('id', $id)->first();


        if ($student != null) {
            if ($student->logo != 'default.png') {

                Storage::disk('public_uploads')->delete('/students_logo/' . $student->logo);

            }//end of if
            Absence::where('student_id', $student->id)->delete();
            Attendance::where('student_id', $student->id)->delete();
            Address::where('student_id', $student->id)->delete();



            $student->delete();

            return redirect()->back()->with('success', 'تم حذف بيانات الطالب بنجاح');
        }
        return redirect()->back()->withErrors(['error' => 'الطالب غير موجود']);

    }


    public function students_data_export()
    {
        $student =  Student::get();
        $school =  School::get();
        return view('dashboard.students.data_export', [
                'student'   => $student,
                'school'   => $school,

            ]);
    }



    public function students_export(Request $r)
    {
        $r->validate([
            'from' => 'required|date|date_format:Y-m-d',
            'to' => 'required|date|date_format:Y-m-d|after_or_equal:from',
            'classroom_id'        =>  'required',
            'school_id'           =>  'required',
            'grade_id'            =>  'required',



        ], [
            'to.after_or_equal' => 'تاريخ النهاية لابد ان اكبر من تاريخ البداية او يساويه',
            'from.date_format' => 'صيغة التاريخ يجب ان تكون yyyy-mm-dd',
            'to.date_format' => 'صيغة التاريخ يجب ان تكون yyyy-mm-dd',


        ]);



        $School = School::find($r->school_id);
        $Students = Student::whereBetween('created_at', [$r->from, $r->to])
        ->where('school_id', $r->school_id)
        ->where('grade_id', $r->grade_id)
        ->where('classroom_id', $r->classroom_id)
        ->get();

        // return Excel::download(new StudentsExport($Students), 'users.xlsx');
        return Excel::download(new StudentsExport($Students), 'طلاب مدرسة '. $School->name . ' من ' . $r->from . ' الي ' .$r->from . '.xlsx');

    }

    public function getStudents($id)
    {

        $ids = DB::table('my__parent_student')->where('my__parent_id', $id)->pluck('student_id');
        return Student::where('bus_id', '!=', null)->whereIn('id', $ids)->pluck("name", "id");

    }
    public function addToBus()
    {

      $schools = School::get();
      $students = Student::get();
      $buses = Bus::get();
      return view('dashboard.students.add-to-bus', [
        'students'   => $students,
        'buses'   => $buses,
        'schools'   => $schools,

    ]);

    }


    public function storeToBus(Request $r)
    {

        try {

            $r->validate([
                'school_id'     =>  'required',
                'bus_id'        =>  'required',
                'student_id.*'  =>   'required',
                'student_id'    =>   'required|array',
                'trip_type'        =>  'required',

            ]);

            if($r->student_id != null) {
                $Students = Student::whereIn('id', $r->student_id)->get();
                $bus = Bus::find($r->bus_id);

                foreach($Students as $Student) {
                    if ($Student->schools->id != $bus->schools->id) {
                        return redirect()->back()->withErrors(['error' => 'لا يمكنك اختيار طالب من مدرسة اخري']);

                    }

                    $Student->bus_id                          = $r->bus_id;
                    $Student->trip_type                       = $r->trip_type;

                    $Student->save();
                }
            }else {
                return redirect()->back()->with(['error' => 'يجب عليك اختيار طالب']);

            }


            return redirect()->back()->with('success', 'تم الاضافة بنجاح');


        }

        catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }
    public function getStudentsSchool(Request $r, $id)
    {

        return Student::where("school_id", $id)->pluck("name", "id");

    }

    public function upload()
    {


        $school =  School::get();


        return view('dashboard.schools.upload', compact('school'));
    }

    public function students_import(Request $request)
    {
        try {
         DB::beginTransaction();

        $validation = Validator::make($request->all(), [
            'attachment' => 'required|mimes:xlsx,xls',
            'grade_id'         =>  'required',
            'school_id'        =>  'required',
            'classroom_id'     =>  'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $school = $request->school_id;
        $grade = $request->grade_id;
        $classroom = $request->classroom_id;
        $file = $request->file('attachment');
        // dd($file);
        $import =  Excel::import(new StudentImport($school, $grade, $classroom), $file);
        DB::commit(); // insert data

        // dd($file);


    }
    catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        DB::rollback();

        // dd($e->failures());
        $failures = $e->failures();
        $failer_array = [];
        foreach ($failures as $failure) {
            $failer_array[] = $failure->errors()[0].$failure->row();
        }

       return redirect()->back()->withErrors(['error' => $failer_array]);

    }



        if (session()->has('errors')) {

            return redirect()->back();
        }else {
            return redirect()->route('dashboard.students.index')->with('success', 'تم حفظ البيانات بنجاح');

        }
    }
    public function download(Request $r)
    {
        // dd(public_path('uploads/import_students_file/import_students_file.xlsx'));
            return response()->download(public_path('uploads/import_students_file/import_students_file.xlsx'));

        return redirect()->back();

    }

    public function attendants_send_messgees($id)
    {
        $student =  Student::find($id);
        return view('dashboard.students.attendants-send-messgees', [
            'student'   => $student,
            'messages'   => Static_messages::get(),
        ]);

    }

    public function attendants_send_messgees_store(Request $r, $id)
    {
        $validation = Validator::make($r->all(), [
            'attendant_id'         =>  'required|exists:attendants,id',
            'message_id'        =>  'required|exists:static_messages,id'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }


        $student =  Student::where('id', $id)->first();
        $parents = $student->my_Parents()->get();
        $messages = Static_messages::where('id', $r->message_id)->first();
        if($parents->isNotEmpty()) {

            foreach ($parents as $parent) {
                AttendantParentMessage::create([
                    'student_id'=> $student->id,
                    'my__parent_id'=> $parent->id,
                    'attendant_id' => $r->attendant_id,
                    'message' =>  $messages->message,
                    'title'=> 'الي ولي امر الطالب : ' . $student->name,

                ]);

            }
            return redirect()->back()->with('success', 'تم ارسال البيانات بنجاح');


        } else {
            return redirect()->back()->withErrors(['error' => 'يجب ان يكون لطالب حساب ولي امر وحد علي الاقل']);

        }


    }


}
