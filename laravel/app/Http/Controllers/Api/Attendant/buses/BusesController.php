<?php

namespace App\Http\Controllers\Api\Attendant\buses;

use App\Http\Controllers\Controller;
use App\Models\{
    Attendant,
    School,
    Bus,
    Student,

};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BusesController extends Controller
{

    public function myBus(Request $request)
    {
        try {
            return sendJSON($request->user()->bus()->with([
                'schools',
                'attendant_driver',
                'students',
            ]));
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function getShow(Request $request, $id)
    {
        try {
            $bus = $request->user()->bus()->with([
                'schools',
                'attendant_admins',
                'attendant_driver',
                'students',
            ]);
            return sendJSON($bus);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }
}
