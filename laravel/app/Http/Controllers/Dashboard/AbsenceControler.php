<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Bus;
use App\Models\My_Parent;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AbsenceControler extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:super|absences-list'])->only(['index']);
        $this->middleware(['permission:super|absences-show'])->only(['show']);
        $this->middleware(['permission:super|absences-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|absences-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|absences-destroy'])->only(['destroy']);
    }
/**
 * Display a listing of absences with optional filtering.
 *
 * This method retrieves a paginated list of absences from the database,
 * with optional filters applied based on the request parameters. The
 * list of absences is displayed along with associated student, school,
 * bus, and parent information.
 *
 * @param Request $r The request instance containing input data for filtering.
 *
 * Filters available:
 * - text: Filters absences where the student's name contains this text.
 * - school_id: Filters absences by the specified school ID.
 * - bus_id: Filters absences by the specified bus ID.
 * - attendence_type: Filters absences by the specified attendance type.
 * - attendence_date: Filters absences by the specified attendance date.
 * - created_at: Filters absences by the date they were created.
 * - my__parent_id: Filters absences by the specified parent ID.
 *
 * @return \Illuminate\View\View The view displaying the filtered list of absences.
 */

    public function index(Request $r)
    {
        $absences = Absence::query()->with('students');
        if (!empty($r->text)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->text, function ($query) use ($r) {
                    return $query->whereHas('students', function ($e) use ($r) {
                        $e->where('name', 'like', "%$r->text%");
                    });

                });
            });
        }

        if (!empty($r->school_id)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);

                });
            });
        }

        if (!empty($r->bus_id)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->bus_id, function ($query) use ($r) {

                    return $query->where('bus_id', $r->bus_id);

                });
            });
        }
        if (!empty($r->attendence_type)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->attendence_type, function ($query) use ($r) {
                    return $query->where('attendence_type', $r->attendence_type);

                });
            });
        }
        if (!empty($r->attendence_date)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->attendence_date, function ($query) use ($r) {
                    return $query->where('attendence_date', $r->attendence_date);

                });
            });
        }
        if (!empty($r->created_at)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->created_at, function ($query) use ($r) {
                    return $query->whereDate('created_at', $r->created_at);

                });
            });
        }

        if (!empty($r->my__parent_id)) {
            $absences = $absences->where(function ($q) use ($r) {
                return $q->when($r->my__parent_id, function ($query) use ($r) {
                    return $query->whereDate('my__parent_id', $r->my__parent_id);

                });
            });
        }
        $buses = Bus::query();
        if (!empty($r->school_id)) {
            $buses = $buses->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id,);

                });
            });
        }
        $buses = $buses->orderBy('id', 'desc')->get();
        $parents = My_Parent::get();
        $absences = $absences->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.absences.index', [
            'absences'   => $absences,
            'schools'   => School::get(),
            'buses'   => $buses,
            'parents'   => $parents,

        ]);

    }

/**
 * Show the form for creating a new absence record.
 *
 * This method retrieves all parent records and passes them to the
 * view responsible for displaying the absence creation form.
 *
 * @return \Illuminate\View\View The view displaying the form to create a new absence.
 */

    public function create()
    {

        $parent = My_Parent ::all();
        return view('dashboard.absences.create', ['parent' => $parent]);


    }

    /**
     * Store a newly created absence in storage.
     *
     * This method stores a new absence record in the database.
     * It validates the request data and checks if the student is subscribed to the bus.
     * If the student is subscribed, it creates a new absence record.
     * If the student is not subscribed, it returns back to the previous page with an error message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $data = $r->all();

        $rules = [
            'my__parent_id'     =>  ['required', 'exists:my__parents,id',Rule::exists('my__parent_student')->where(function ($query) use ($r, $data) {
                return $query->where('my__parent_id',  $data['my__parent_id'])->where('student_id',  $data['student_id']);
            })],
            'student_id'        =>  ['required', 'exists:students,id'],
            'attendence_date'   =>  'required|date|date_format:Y-m-d|after:'.date('Y-m-d'),
            'attendence_type'   => 'required|in:full_day,end_day,start_day',
        ];
          $validator = Validator::make($data, $rules);
          if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



            $Student = Student::where('id', $r->student_id)
            ->where('bus_id', '!=', null)->first();
            if ($Student != null) {
                if (in_array($data['attendence_type'], tr_student_check_has_trip_type($Student->trip_type))) {

                    Absence::create([
                        'created_by'=> 'staff',
                        'student_id'         =>    $data['student_id'],
                        'attendence_date'    =>  $data['attendence_date'],
                        'my__parent_id'      =>    $data['my__parent_id'],
                        'school_id'     =>    $Student->school_id,
                        'bus_id'        =>    $Student->bus_id,
                        'attendence_type' =>  $data['attendence_type']
                        ]);
                        return redirect()->route('dashboard.absences.index')->with('success', 'تم اضافة البيانات بنجاح');
                }else {
                    return redirect()->back()->withErrors(['error' => 'يجب ان يكون الطالب مشترك الخدمة']);

                }


            }

            return redirect()->back()->withErrors(['error' => 'يجب ان يكون الطالب مشترك في الباص']);


    }


    /**
     * Show the specified absence.
     *
     * This method shows the details of a specified absence.
     * It takes an id parameter which is the id of the absence.
     * It retrieves the absence record from the database and then
     * passes it to the view.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absence = Absence::find($id);

        return view('dashboard.absences.show', [
            'absence' => $absence,
        ]);
    }

    /**
     * Update the specified absence in storage.
     *
     * This method validates the request data and updates the absence record
     * with the specified id. It ensures the student is subscribed to the bus
     * and that the attendance type is valid for the student's trip type.
     * If the update is successful, it redirects to the absences index page
     * with a success message. Otherwise, it returns back with an error message.
     *
     * @param \Illuminate\Http\Request $r The incoming request instance containing the update data.
     * @param int $id The id of the absence to be updated.
     * @return \Illuminate\Http\Response A redirect response upon success or failure.
     */

    public function update(Request $r, $id)
    {

            $Absence = Absence::find($id);
            $r->validate([

                'attendence_date'   =>  'required|date|date_format:Y-m-d|after:'.date('Y-m-d'),
                'attendence_type'   => 'required|in:full_day,end_day,start_day',

            ]);

            $data = $r->all();

            $Student = Student::where('id', $Absence->student_id)->where('bus_id', '!=', null)->first();
            if ($Student != null) {

                if (in_array($data['attendence_type'], tr_student_check_has_trip_type($Student->trip_type))) {

                    $Absence->update([
                        'updated_by'=> 'staff',

                        'attendence_date'   =>   $data['attendence_date'],
                        'attendence_type'   =>   $data['attendence_type'],
                    ]);
                        return redirect()->route('dashboard.absences.index')->with('success', 'تم اضافة البيانات بنجاح');
                }else {
                    return redirect()->back()->withErrors(['error' => 'يجب ان يكون الطالب مشترك الخدمة']);

                }

            }

            return redirect()->back()->withErrors(['error' => 'يجب ان يكون الطالب مشترك في الباص']);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Absence = Absence::FindOrFail($id);
        $Absence->delete();
        return redirect()->back()->with('success', 'نم الحذف بنجاح');
    }


}
