<?php

namespace App\Http\Controllers\Dashboard;

use App\Enum\TripStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Attendant;
use App\Models\Bus;
use App\Models\School;
use App\Models\Student;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TripControler extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:super|trips-list'])->only(['index']);
        $this->middleware(['permission:super|trips-show'])->only(['show']);
        $this->middleware(['permission:super|trips-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|trips-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|trips-destroy'])->only(['destroy']);
    }

    public function index(Request $r)
    {


        $trips = Trip::query();

        if ( !empty($r->school_id)) {
            $trips = $trips->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id);

                });
            });
        }

        if ( !empty($r->bus_id)) {
            $trips = $trips->where(function ($q) use ($r) {
                return $q->when($r->bus_id, function ($query) use ($r) {
                    return $query->where('bus_id', $r->bus_id);

                });
            });
        }
        if ( !empty($r->trip_type)) {
            $trips = $trips->where(function ($q) use ($r) {
                return $q->when($r->trip_type, function ($query) use ($r) {
                    return $query->where('trip_type', $r->trip_type);

                });
            });
        }
        if ( !empty($r->trips_date)) {
            $trips = $trips->where(function ($q) use ($r) {
                return $q->when($r->trips_date, function ($query) use ($r) {
                    return $query->where('trips_date', $r->trips_date);

                });
            });
        }
        if ( !empty($r->status)) {
            if ($r->status == 'completed') {
                $status = 1;
            }
            if ($r->status == 'not_complete') {
                $status = 0;
            }
            $trips = $trips->where(function ($q) use ($r, $status) {
                return $q->when($r->status, function ($query) use ($r, $status) {
                    return $query->where('status', $status);

                });
            });
        }

        $buses = Bus::query();
        if ( !empty($r->school_id)) {
            $buses = $buses->where(function ($q) use ($r) {
                return $q->when($r->school_id, function ($query) use ($r) {
                    return $query->where('school_id', $r->school_id,);

                });
            });
        }
        $buses = $buses->orderBy('id', 'desc')->get();
        $trips = $trips->orderBy('id', 'desc')->paginate(10);
        return view('dashboard.trips.index', [
            'trips'   => $trips,
            'buses'   => $buses,
            'schools'   => School::get(),

        ]);

    }

    public function create($id) {

        $attendant = Attendant::where('id', $id)->first();

        if (isset($attendant) != null && $attendant->bus_id != null) {
            return view('dashboard.trips.create',[
                'attendant' => $attendant,
            ]);
        }
        return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

    }
    public function store(Request $r, $id) {

        $attendant = Attendant::where('id', $id)->first();
        if (isset($attendant) != null && $attendant->bus_id != null) {

            $rules = [
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'trip_type'          => ['nullable', 'in:start,end',Rule::unique('trips')->where(function ($query) use ($r, $attendant) {
                    return $query->where('trips_date', date('Y-m-d'))->where('bus_id', $attendant->bus_id);
                })],
            ];
            $r->validate($rules);

            $trip = new Trip;
            $trip->latitude = $r->latitude;
            $trip->longitude = $r->longitude;
            $trip->bus_id = $attendant->bus_id;
            $trip->school_id = $attendant->school_id;;
            $trip->trips_date = date('Y-m-d');
            $trip->trip_type = $r->trip_type;
            $trip->save();
            return redirect()->route('dashboard.trips.index')->with('success', 'تم حفظ البيانات بنجاح');


        }
        return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);

    }
    public function show($id)
    {
        $trip = Trip::where('id', $id)->first();
        return view('dashboard.trips.show', [
            'trip'   => $trip,
        ]);

    }

    public function showOnMap($id)
    {
        $trip = Trip::where('id', $id)->with('routes')->first();
        // dd($trip);
        return view('dashboard.trips.map', [
            'trip'   => $trip,
        ]);
    }

    public function edit($id)
    {


        $trip = Trip::where('id', $id)->first();
        if($trip->trips_date == date('Y-m-d')) {

            return view('dashboard.trips.edit', [
                'trip'   => $trip,
            ]);

        }

        return redirect()->back()->withErrors(['error' => 'لا تستطيع التعديل علي بيانات الرحلة بعد انتهاء اليوم']);


    }

    public function update(Request $r,$id)
    {
        $trip = Trip::where('id', $id)->first();
        if($trip->trips_date == date('Y-m-d')) {


            $rules = [
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'status' => 'required|in:not_complete,completed',

            ];
            $r->validate($rules);

            $trip->latitude = $r->latitude;
            $trip->longitude = $r->longitude;

            if ($r->status == 'completed') {
                $status = 1;
            }else {
                $status = 0;

            }

            if($status != $trip->status) {
                if ($status == 0 && $trip->status == 1) {

                    if ($trip->trip_type == 'start') {
                        $students_at_school= Attendance::where('trip_id', $trip->id)->where('attendence_status', 2)->get();
                        if($students_at_school->isNotEmpty()) {

                            foreach ($students_at_school as $students_at_school) {
                                    $students_at_school->update([
                                        'attendence_status' => 1,

                                    ]);

                            }
                        }
                    }

                    $trip->status = $status;
                }

                if ($status == 1 && $trip->status == 0) {
                    $students_attendence_ids = $trip->attendances->pluck('student_id');
                    $students_not_attendence = Student::where('bus_id', $trip->bus_id)
                    ->whereIn('trip_type', attendence_absence_type($trip->trip_type))
                    ->whereNotIn('id', $students_attendence_ids)
                    ->get();

                    if($students_not_attendence->isNotEmpty()) {

                        foreach ($students_not_attendence as $not_attendence) {
                            Attendance::create([
                                'student_id'=> $not_attendence->id,
                                'attendence_date'=> date('Y-m-d'),
                                'school_id' => $trip->school_id,
                                'attendence_status'=> 0,
                                'trip_id' =>  $trip->id,
                            ]);
                        }
                    }

                    $students_presence = Attendance::where('trip_id', $trip->id)->where('attendence_status', 1)->get();

                    $attendence_status = 0;


                    if ($trip->trip_type == 'end') {

                        $attendence_status = 3;
                    }
                    if ($trip->trip_type == 'start') {
                        $attendence_status = 2;
                    }

                    if($students_presence->isNotEmpty()) {

                        foreach ($students_presence as $student_presence) {
                                $student_presence->update([
                                    'attendence_status' => $attendence_status,

                                ]);

                        }
                    }
                    $trip->status = $status;
                }

            }
            $trip->save();

            return redirect()->route('dashboard.trips.index')->with('success', 'تم حفظ البيانات بنجاح');

        }

        return redirect()->back()->withErrors(['error' => 'لا تستطيع التعديل علي بيانات الرحلة بعد انتهاء اليوم']);

    }

    /**
     * Delete the trip with the given id
     * @param int $id The ID of the trip to delete
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($id)
    {
        $trip = Trip::where('id', $id)->first();
        $trip->delete();
        return redirect()->route('dashboard.trips.index')->with('success', 'تم حذف بيانات الرحلة بنجاح');
    }


    /**
     * End the trip by setting the status to 1 and saving the model
     *
     * @param int $id The ID of the trip to end
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function end($id)
    {
        $trip = Trip::where('id', $id)->first();
        $trip->status = TripStatusEnum::COMPLETED;
        $trip->end_at = Carbon::now();
        $trip->save();

        return redirect()->route('dashboard.trips.index')->with('success', 'تم انهاء الرحلة بنجاح');

    }


}
