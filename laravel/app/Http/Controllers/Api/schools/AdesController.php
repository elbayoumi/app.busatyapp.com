<?php

namespace App\Http\Controllers\Api\schools;

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
            $adesSchool=AdesSchool::where('school_id',$request->user()->id)->whereIn('to',['all','school'])->select('ades_id')->with('ades')->get();
            return JSON($adesSchool);
        } catch (\Exception $e) {
            return JSONerror($e->getMessage(), 500);
        };
    }

}
