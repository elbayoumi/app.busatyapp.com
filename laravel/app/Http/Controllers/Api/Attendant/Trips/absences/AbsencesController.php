<?php

namespace App\Http\Controllers\Api\Attendant\Trips\absences;

use App\Http\Controllers\Controller;
use App\Models\{
    Absence,
    Attendant,
    Student,
    Attendance,
    My_Parent,
    Trip,
    TripStudent
};
use App\Notifications\Notify;
use App\Notifications\StudentNotification;
use Illuminate\{
    Http\Request,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AbsencesController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Store absence from home
     * @param Request $request
     * @param int $student_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAbsenceFromHome(Request $request, $student_id)
    {
        $student = Student::find($student_id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $trip = Trip::where('bus_id', $student->bus_id)
            ->startDay()
            ->notCompleted()
            ->first();

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        // Create or update the trip-student status to 'absent'
        TripStudent::updateOrCreate(
            [
                'trip_id' => $trip->id,
                'student_id' => $student_id,
            ],
            [
                'status' => 'absent',
            ]
        );

        return $this->absenceProcess($request, $student_id, 'start_day');
    }

    public function storeAbsenceFromSchool(Request $request, $student_id)
    {
        $student = Student::find($student_id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $trip = Trip::where('bus_id', $student->bus_id)
            ->endDay()
            ->notCompleted()
            ->first();

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        // Create or update the absence status
        TripStudent::updateOrCreate(
            [
                'trip_id' => $trip->id,
                'student_id' => $student_id,
            ],
            [
                'status' => 'absent',
            ]
        );

        return $this->absenceProcess($request, $student_id, 'end_day');
    }

    private function absenceProcess($request, $student_id, $attendence_type)
    {
        try {
            
            $current_date = Carbon::now()->format('Y-m-d');

            $student = Student::where('id', $student_id)->where('school_id', $request->user()->school_id)->where('bus_id', $request->user()->bus_id);
            $absence = Absence::where('student_id', $student_id)->where('attendence_date', $current_date);
            $attendence = Attendance::where('student_id', $student_id)->where('attendence_date', $current_date)->where('attendance_type', $attendence_type);
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
                    return response()->json(['errors' => true, 'message' => __('The student must be present at the same school, on the same bus, and not absent on this day')], 403);
            }

            switch ($attendence_type_condtion) {
                case $attendence_type:
                    // $student->absences()->create([

                    // ]);
                    $absence = new Absence;
                    $student = $student->with('my_Parents')->first();
                    $first_parant = count($student->my_Parents)<1?null: $student->my_Parents[0];
                    $absence->school_id = $request->user()->school_id;
                    $absence->bus_id = $request->user()->bus_id;
                    $absence->my__parent_id = $first_parant->id??null;
                    $absence->student_id = $student_id;
                    $absence->attendence_date = $current_date;
                    $absence->created_by = $request->user()->type;
                    break;
                case 'full_day':
                    $absence = $attendence_type_current;
                    $absence->updated_by = $request->user()->type;
                    break;
            }

            $student=Student::with('schools')->find($student_id);
            $title = [
                'start_day' => 'صباح الخير',
                'end_day' => "مساء الخير"
            ];

            $body = "{$student->name} سجل غائب في باص مدرسة {$student->schools->name}";

            $notifications_type='no-tracking';

            // Notification::send($student, new StudentNotification($title[$attendence_type], $body,$notifications_type));
            // $topic='student'.$student->id ;
            // $topic =$student->id.'/'.$trip->id;
            // $topic = (string) 'student' . $student->id;
            $topic= $student->customIdentifier->identifier;

            Notification::send($student, new Notify($title[$attendence_type], $body,$topic,My_Parent::class, $notifications_type));

            $absence->attendence_type = $attendence_type_condtion;
            // return $absence->toArray();
            $absence->save();
            // return $absence;

            if($attendence->exists()) {
                $attendence->delete();
            }

            return response()->json(['errors' => false, 'message' =>  $absence], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


// private function absenceProcess($request, $student_id, $attendence_type)
// {
//     try {
//         $current_date = Carbon::now()->format('Y-m-d');

//         $student = Student::where('id', $student_id)
//             ->where('school_id', $request->user()->school_id)
//             ->where('bus_id', $request->user()->bus_id);

//         $absence = Absence::where('student_id', $student_id)
//             ->where('attendence_date', $current_date);

//         $attendence = Attendance::where('student_id', $student_id)
//             ->where('attendence_date', $current_date)
//             ->where('attendance_type', $attendence_type);

//         $attendence_type_condtion = $attendence_type;

//         // Check if the student has an absence record
//         if ($absence->exists()) {
//             $attendence_type_current = $absence->first();
//             $attendence_type_condtion = $attendence_type == $attendence_type_current->attendence_type
//                 ? $attendence_type
//                 : 'full_day';
//         }

//         $absence = $absence->whereIn('attendence_type', [$attendence_type, 'full_day']);

//         if ((!$student->exists()) || $absence->exists()) {
//             return response()->json(
//                 ['errors' => true, 'message' => __('The student must be present at the same school, on the same bus, and not absent on this day')],
//                 403
//             );
//         }

//         switch ($attendence_type_condtion) {
//             case $attendence_type:
//                 $absence = new Absence;
//                 $student = $student->with('my_Parents')->first();
//                 $first_parant = count($student->my_Parents) < 1 ? null : $student->my_Parents[0];

//                 $absence->school_id = $request->user()->school_id;
//                 $absence->bus_id = $request->user()->bus_id;
//                 $absence->my__parent_id = $first_parant->id ?? null;
//                 $absence->student_id = $student_id;
//                 $absence->attendence_date = $current_date;
//                 $absence->created_by = $request->user()->type;
//                 break;

//             case 'full_day':
//                 $absence = $attendence_type_current;
//                 $absence->updated_by = $request->user()->type;
//                 break;
//         }

//         $student = Student::with('schools')->find($student_id);
//         $title = [
//             'start_day' => 'صباح الخير',
//             'end_day' => "مساء الخير"
//         ];

//         $body = "{$student->name} سجل غائب في باص مدرسة {$student->schools->name}";

//         $notifications_type = 'no-tracking';

//         $topic = $student->customIdentifier->identifier;

//         Notification::send(
//             $student,
//             new Notify($title[$attendence_type], $body, $topic, My_Parent::class, $notifications_type)
//         );

//         $absence->attendence_type = $attendence_type_condtion;

//         if ($attendence->exists()) {
//             $attendence->delete();
//         }

//         return response()->json(['errors' => false, 'message' => $absence], 200);
//     } catch (\Exception $exception) {
//         return JSONerror($exception->getMessage(), 500);
//     }
// }




    public function create(Request $request)
    {
        //get all students with my_Parents absences
        try {
            $user = Attendant::findOrFail($request->user()->id);
            $students = Student::where('bus_id', $user->bus_id)->with([
                'my_Parents',
                'absences',
            ])
                ->orderBy('id', 'desc')
                ->get();

                switch($students->count() > 0){
                    case true:
                        return response()->json([
                            'data' => [
                                'childrens' => $students,
                                'attendence_type' => ['full_day', 'start_day', 'end_day'],
                            ],

                            'message' => __("success message"),
                            'errors' => false,
                        ]);
                }

            return response()->json([
                'message' => __('children not found'),
                'errors' => true,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }

}
