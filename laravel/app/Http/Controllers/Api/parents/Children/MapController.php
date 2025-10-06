<?php

namespace App\Http\Controllers\Api\parents\Children;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\My_Parent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $ids = My_Parent::where('id', $request->user()->id)->first()->id;
            $ids = DB::table('my__parent_student')->where('my__parent_id', $request->user()->id)->pluck('student_id');
            $student = Student::whereIn('id', $ids)->where('id', $id)
                ->with([
                    'schools',
                    'gender',
                    'religion',
                    'typeBlood',
                    'bus',
                    'grade',
                    'classroom',
                    'my_Parents',
                    'attendance',
                    'absences',
                ])
                ->first();

            if ($student != null &&  $student->bus_id != null) {

                $buses = Bus::where('id', $student->bus_id)->first();
            }


            if ($student != null && $buses != null) {


                $attendance = $student->attendance()->with(['trip'])
                    ->where('attendence_status', 1)
                    ->where('attendence_date', date('Y-m-d'));
                $attendance = $attendance->where(function ($q) {
                    return $q->whereHas('trip', function ($e) {
                        $e->where('status', 0);
                    });
                });

                $attendance = $attendance->first();

                if ($attendance != null && $attendance->trip != null) {


                    $latitude = $attendance->trip->latitude;
                    $longitude = $attendance->trip->longitude;
                    return response()->json([
                        'data' => [
                            'latitude' =>   $latitude,
                            'longitude' =>   $longitude,
                        ],
                        'message' => 'success message',
                        'errors' => false
                    ]);
                }
                return response()->json([
                    'message' => 'The student is not on the bus at the moment',
                    'errors' => true,
                ], 500);
            }

            return response()->json(['errors' => true, 'message' => 'Something was wrong'], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
