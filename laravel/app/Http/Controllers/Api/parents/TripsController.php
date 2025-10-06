<?php

namespace App\Http\Controllers\Api\parents;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    public function index(Request $request)
    {
    try {
        $tripArray = $request->user()
        ->students()
        ->whereHas('bus.trip', function ($q) {
            $q->notCompleted();
        })
        ->with([
            'bus.trip' =>function ($query) {
                $query->notCompleted();
            },
            'schools',
            'temporaryAddresses',
            'bus.trip.creator'
        ])
        ->get()
        ->toArray();


        return JSON($tripArray); // Fixed typo: JSON should be response()->json

    } catch (\Exception $exception) {
        return response()->json([
            'message' => $exception->getMessage(),
            'status' => false
        ], 500);
    }
    }
    public function show(Request $request,$trip_id)
    {
    try {
        $tripArray = $request->user()
        ->students()
        ->whereHas('bus.trip', function ($q) use ($trip_id) {
            $q->where('trips.id', $trip_id)->notCompleted(); // وضح أن الـ id يأتي من trips
        })
        ->with([
            'bus:id',
            'bus.trip' => function ($q) use ($trip_id) {
                $q->where('trips.id', $trip_id); // وضح أن الـ id يأتي من trips
            }
        ])
        ->get([
            'students.id', 'students.name', 'students.bus_id' // وضح أن الـ id والـ bus_id من جدول students
        ])
        ->toArray();

        return JSON($tripArray); // Fixed typo: JSON should be response()->json

    } catch (\Exception $exception) {
        return response()->json([
            'message' => $exception->getMessage(),
            'status' => false
        ], 500);
    }
    }
}
