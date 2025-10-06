<?php

namespace App\Http\Controllers\Api\Attendant\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    NotificationAttendant,
    NotificationParant,
    Student,
    Attendant,
    My__parent_student,
    NotificationSchool,
};
class AttendantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {
            $notificationAttendant = $request->user()->notification();

            if (!empty($request->text)) {
                $notificationAttendant = $notificationAttendant->where('name', 'like', "%$request->text%");
            }

            $notificationAttendant = $notificationAttendant->orderBy('id', 'desc')->get();

            return JSON($notificationAttendant);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        //
    }
}
