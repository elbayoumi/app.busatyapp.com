<?php

namespace App\Http\Controllers\Api\schools\absences;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Bus;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AbsenceControler extends Controller
{
    public function storeAbsenceFromHome(Request $request, $student_id)
    {
        return $this->absenceProcess($request, $student_id, 'start_day');
    }
    public function storeAbsenceFromSchool(Request $request, $student_id)
    {
        return $this->absenceProcess($request, $student_id, 'end_day');
    }
    private function absenceProcess($request, $student_id, $attendence_type)
    {
        try {
            $current_date = Carbon::now()->format('Y-m-d');
            $student = Student::where('id', $student_id)->where('school_id', $request->user()->school_id)->where('bus_id', $request->user()->bus_id);
            $absence = Absence::where('student_id', $student_id)->where('attendence_date', $current_date);
            $attendence_type_condtion = $attendence_type;
            switch ($absence->exists()) {
                case true:
                    $attendence_type_current = $absence->first();

                    $attendence_type_condtion = $attendence_type == $attendence_type_current->attendence_type ? $attendence_type : 'full_day';
                    break;
            }

            $absence = $absence->whereIn('attendence_type', [$attendence_type, 'full_day']);
            switch ((!$student->exists()) || $absence->exists()) {
                case true:
                    return response()->json([
                        'errors' => true,
                        'message' => __('The student must be present at your school and on the same bus, and not absent on this day')
                    ], 500);
            }

            switch ($attendence_type_condtion) {
                case $attendence_type:
                    $absence = new Absence;
                    $student = $student->with('my_Parents')->first();
                    $first_parant = $student->my_Parents[0];
                    $absence->school_id = $request->user()->school_id;
                    $absence->bus_id = $request->user()->bus_id;
                    $absence->my__parent_id = $first_parant->id;
                    $absence->student_id = $student_id;
                    $absence->attendence_date = $current_date;
                    $absence->created_by = $request->user()->type;
                    break;
                case 'full_day':
                    $absence = $attendence_type_current;
                    $absence->updated_by = $request->user()->type;
                    break;
            }
            $absence->attendence_type = $attendence_type_condtion;

            $absence->save();


            return response()->json(['errors' => false, 'message' =>  $absence], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function index(Request $r)
    {
        try {

            $user = School::where('id', $r->user()->id)->first();

            if ($user != null) {
                $text = isset($r->text) && $r->text != '' ? $r->text : null;
                $bus_id = isset($r->bus_id) && $r->bus_id != '' ? $r->bus_id : null;
                $attendence_type = isset($r->attendence_type) && $r->attendence_type != '' ? $r->attendence_type : null;
                $attendence_date = isset($r->attendence_date) && $r->attendence_date != '' ? $r->attendence_date : null;
                $created_at = isset($r->created_at) && $r->created_at != '' ? $r->created_at : null;

                $absences = Absence::where('school_id', $user->id)->with(['schools', 'students', 'parent', 'bus']);

                if ($text != null) {
                    $absences = $absences->where(function ($q) use ($r) {
                        return $q->when($r->text, function ($query) use ($r) {
                            return $query->whereHas('students', function ($e) use ($r) {
                                $e->where('name', 'like', "%$r->text%");
                            });
                        });
                    });
                }


                if ($bus_id != null) {
                    $absences = $absences->where(function ($q) use ($r) {
                        return $q->when($r->bus_id, function ($query) use ($r) {
                            return $query->where('bus_id', $r->bus_id);
                        });
                    });
                }
                if ($attendence_type != null) {
                    $absences = $absences->where(function ($q) use ($r) {
                        return $q->when($r->attendence_type, function ($query) use ($r) {
                            return $query->where('attendence_type', $r->attendence_type);
                        });
                    });
                }
                if ($attendence_date != null) {
                    $absences = $absences->where(function ($q) use ($r) {
                        return $q->when($r->attendence_date, function ($query) use ($r) {
                            return $query->where('attendence_date', $r->attendence_date);
                        });
                    });
                }
                if ($created_at != null) {
                    $absences = $absences->where(function ($q) use ($r) {
                        return $q->when($r->created_at, function ($query) use ($r) {
                            return $query->whereDate('created_at', $r->created_at);
                        });
                    });
                }

                $buses = Bus::query()->where('school_id', $user->id)->orderBy('id', 'desc')->get();

                $absences = $absences->orderBy('id', 'desc')->paginateLimit();
                return response()->json([
                    'data' => [
                        'absences' => $absences,
                        'buses' => $buses,
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



    public function show(Request $r, $id)
    {
        try {

            $user = School::where('id', $r->user()->id)->first();
            $absences = Absence::where('school_id', $user->id)->where('id', $id)->with(['schools', 'students', 'parent', 'bus'])->first();

            if ($user != null && $absences != null) {

                return response()->json([
                    'data' => $absences,
                    'message' => 'success message',
                    'status' => true
                ]);
            }
            return response()->json(['errors' => true, 'message' => __("success message")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
