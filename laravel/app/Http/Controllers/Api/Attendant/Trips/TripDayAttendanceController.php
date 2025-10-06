<?php

namespace App\Http\Controllers\Api\Attendant\Trips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendant\Trips\CheckInRequest;
use App\Http\Requests\Attendant\Trips\CheckOutRequest;
use App\Http\Requests\Attendant\Trips\MarkStatusRequest;
use App\Models\TripDay;
use App\Models\TripDayAttendance;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TripDayAttendanceController extends Controller
{
    /**
     * Display attendance records for a given trip day
     */
    public function index(TripDay $tripDay)
    {
        // Get all attendance records for the given trip day with student & creator relations
        $attendances = TripDayAttendance::with(['student', 'creator'])
            ->where('trip_day_id', $tripDay->id)
            ->get();

        return JSON($attendances);
    }

    /**
     * Register student Check-in
     */
    public function checkIn(CheckInRequest $request, TripDay $tripDay)
    {
        $attendance = TripDayAttendance::firstOrNew([
            'trip_day_id' => $tripDay->id,
            'student_id'  => $request->student_id,
        ]);

        $attendance->status        = 'present';
        $attendance->check_in_at   = now();
        $attendance->check_in_lat  = $request->lat;
        $attendance->check_in_long = $request->long;

        // Associate the current user as the creator of this action
        $attendance->creator()->associate($request->user());
        $attendance->save();

        return JSON($attendance, 201);
    }

    /**
     * Register student Check-out
     */
    public function checkOut(CheckOutRequest $request, TripDay $tripDay)
    {
        try {
            $attendance = TripDayAttendance::where('trip_day_id', $tripDay->id)
                ->where('student_id', $request->student_id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return JSONerror('Attendance record not found for this student on this trip day.', 404);
        }

        $attendance->check_out_at   = now();
        $attendance->check_out_lat  = $request->lat;
        $attendance->check_out_long = $request->long;

        // Associate the current user as the one who performed check-out
        $attendance->creator()->associate($request->user());
        $attendance->save();

        return JSON($attendance);
    }

    /**
     * Mark student as Absent
     */
    public function markAbsent(MarkStatusRequest $request, TripDay $tripDay)
    {
        $attendance = TripDayAttendance::updateOrCreate(
            ['trip_day_id' => $tripDay->id, 'student_id' => $request->student_id],
            ['status' => 'absent']
        );

        $attendance->creator()->associate($request->user());
        $attendance->save();

        return JSON($attendance);
    }

    /**
     * Mark student as Excused
     */
    public function markExcused(MarkStatusRequest $request, TripDay $tripDay)
    {
        $attendance = TripDayAttendance::updateOrCreate(
            ['trip_day_id' => $tripDay->id, 'student_id' => $request->student_id],
            ['status' => 'excused']
        );

        $attendance->creator()->associate($request->user());
        $attendance->save();

        return JSON($attendance);
    }
}
