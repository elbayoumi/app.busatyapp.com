<?php

namespace App\Http\Controllers\Api\Callback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripRoute;
use Illuminate\Support\Facades\DB;

class   TrackingController extends Controller
{


    public function storeBatch(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|array|min:1',
            'data.*.trip_id' => 'required|integer',
            'data.*.bus_id' => 'nullable|integer',
            'data.*.latitude' => 'required|string',
            'data.*.longitude' => 'required|string',
            'data.*.type' => 'nullable|string',
            'data.*.created_at' => 'nullable|string',
        ]);

        DB::table('trip_routes')->insert($validated['data']);

        return response()->json([
            'status' => 'batch_saved',
            'count' => count($validated['data'])
        ]);
    }
}
