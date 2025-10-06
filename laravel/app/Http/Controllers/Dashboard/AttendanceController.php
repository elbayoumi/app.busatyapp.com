<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\Trip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:super|attendances-list'])->only(['index']);
        $this->middleware(['permission:super|attendances-show'])->only(['show']);
        $this->middleware(['permission:super|attendances-create'])->only(['create', 'store']);
        $this->middleware(['permission:super|attendances-edit'])->only(['edit', 'update']);
        $this->middleware(['permission:super|attendances-destroy'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    @mixin show
    */
    public function index(Request $r, $id)
    {
        $trip = Trip::where('id', $id)->first();
        $absence_ids = Absence::whereIn('attendence_type', student_trip_type_check($trip->trip_type))
            ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();
        return view('dashboard.attendance.index', [
            'trip'   => $trip,
            'absence_ids'   => $absence_ids,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $trip = Trip::where('id', $id)->first();
        if ($trip->status != 0) {

            return redirect()->back()->withErrors(['error' => 'لا يمكن اضافة الغياب بعد انتهاء الرحلة']);
        }

        $absence_ids = Absence::where('attendence_type', 'full_day')
            ->where('bus_id', $trip->bus_id)->where('attendence_date', $trip->trips_date)->pluck('student_id')->toArray();

        return view('dashboard.attendance.create', [
            'trip'   => $trip,
            'absence_ids'   => $absence_ids,

        ]);
    }
    /**
     * Store attendance records for a specific trip.
     *
     * This method validates and stores attendance records for students on a given trip.
     * It checks if the trip has not ended and validates attendance data for each student.
     * If validation passes, it creates an attendance record in the database for each student.
     * If any validation fails, it returns back with the appropriate error messages.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  int  $id  The trip ID
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r, $id)
    {

        $trip = Trip::where('id', $id)->first();
        if ($trip->status != 0) {

            return redirect()->route('dashboard.buses.index')->withErrors(['error' => 'لا يمكن اضافة الغياب بعد انتهاء الرحلة']);
        }

        $rules = [];
        $data = [];



        if ($trip != null && $r->attendences != null) {
            foreach ($r->attendences as $studentid => $attendence) {
                $data['student_id'] = $studentid;
                $data['status'] = $attendence;
                $data['id'] = $studentid;;

                $rules = [
                    'status' => ['required', 'in:presence,absent'],
                    'id' => ['required', Rule::exists('students')->where(function ($query) use ($r, $trip, $studentid) {
                        return $query->where('id', $studentid)->whereIn('trip_type', attendence_absence_type($trip->trip_type));
                    })],
                    'student_id'  => ['required', 'exists:students,id', Rule::unique('attendances')->where(function ($query) use ($r, $trip) {
                        return $query->where('trip_id', $trip->id);
                    })],
                ];
                $validator = Validator::make($data, $rules, [
                    'status.in' => $studentid . ' . ' . 'يجب ان تكون الحالة  تساوي' . ' : ' . 'presence, absent',
                    'status.required',
                    $studentid . ' . ' . 'الحالة مطلوبة',
                    'student_id.required',
                    $studentid . ' . ' . 'id' . 'الطالب مطلوب',
                    'student_id.exists',
                    $studentid . ' . ' . 'الطالب غير موجود',
                    'student_id.unique',
                    $studentid . ' . ' .  'تم اضافة غياب الطالب من قبل',
                    'id.exists',
                    $studentid . ' . ' .  'غير مشترك في الخدمة',
                    'id.*',
                    $studentid . ' . ' .  'غير مشترك في الخدمة',

                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }
                if ($attendence == 'presence') {
                    $attendence_status = 1;
                } else if ($attendence == 'absent') {
                    $attendence_status = 0;
                }


                Attendance::create([
                    'student_id' => $studentid,
                    'attendence_date' => date('Y-m-d'),
                    'school_id' => $trip->school_id,
                    'attendence_status' => $attendence_status,
                    'trip_id' =>  $trip->id,
                ]);
            }

            return redirect()->back()->with('success', 'تم اضافة البيانات با نجاح');
        }


        return redirect()->back()->withErrors(['error' => 'يجب اضافة عنصر واحد علي الاقل']);
    }
    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendances = Attendance::where('id', $id)->first();
        return view('dashboard.attendance.show', [
            'attendances'   => $attendances,

        ]);
    }

    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/dashboard/attendances/{id}",
     *      tags={"Attendance"},
     *      summary="Update attendance",
     *      description="Update an attendance",
     *      @OA\Parameter(
     *          name="id",
     *          description="Attendance id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="attendences",
     *                  type="string",
     *                  enum={"presence", "absent", "at_home"},
     *                  example="presence"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Attendance updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Attendance updated successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Attendance not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Attendance not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\AdditionalProperties(
     *                      type="array",
     *                      @OA\Items(type="string")
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function update(Request $r, $id)
    {
        $attendances = Attendance::where('id', $id)->first();
        if ($attendances != null && $attendances->trip->status == 0) {
            if ($r->attendences == 'presence') {

                $attendence_status = 1;
            } else if ($r->attendences == 'absent') {

                $attendence_status = 0;
            } else if ($r->attendences == 'at_home' && $attendances->trip->trip_type == 'end') {

                $attendence_status = 3;
            } else {
                return redirect()->back()->withErrors(['error' => 'يوجد خطاء في البيانات']);
            }
            $attendances->update([
                'attendence_status' => $attendence_status
            ]);
            return redirect()->back()->with('success', 'تم اضافة البيانات بنجاح');
        } else {
            return redirect()->back()->withErrors(['error' => 'لا يمكن التعديل علي رحلة مكتملة']);
        }
    }
    /**
     * Delete attendance record.
     *
     * This method deletes an attendance record. If the record exists and the trip is not finished,
     * it deletes the record and redirects back with a success message. Otherwise, it redirects back
     * with an error message.
     *
     * @param  int  $id  The attendance ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendances = Attendance::where('id', $id)->first();
        if ($attendances != null && $attendances->trip->status == 0) {
            $attendances->delete();

            return redirect()->back()->with('success', 'نم الحذف بنجاح');
        } else {
            return redirect()->back()->withErrors(['error' => 'لا يمكن التعديل علي رحلة مكتملة']);
        }
    }

    /**
     * End a student's trip and update attendance status.
     *
     * This method checks if the attendance record exists and the related trip is ongoing and of type 'end'.
     * If conditions are met, it updates the attendance status to indicate the student's trip has ended.
     * It then redirects back with a success message. If the conditions are not met, it redirects back
     * with an error message.
     *
     * @param int $id The attendance ID
     * @return \Illuminate\Http\Response
     */

    public function attendances_trip_students_end($id)
    {
        $attendances = Attendance::where('id', $id)->first();
        if ($attendances != null && $attendances->trip->status == 0 && $attendances->trip->trip_type == 'end') {
            $attendances->update([
                'attendence_status' => 3,
            ]);

            return redirect()->back()->with('success', 'تم انهاء رحلة الطالب');
        } else {
            return redirect()->back()->withErrors(['error' => 'لا يمكن التعديل علي الغياب']);
        }
    }
}
