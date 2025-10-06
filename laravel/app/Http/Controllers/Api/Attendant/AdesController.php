<?php

namespace App\Http\Controllers\Api\Attendant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    AdesSchool,
 } ;
class AdesController extends Controller
{

    public function index(Request $request)
    {
        try{
            $usr=$request->user();

            $adesSchool=AdesSchool::where('school_id',$usr->school_id)->whereIn('to',['all','attendance'])->select('ades_id')->with('ades')->get();

            return JSON($adesSchool);

        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

}
