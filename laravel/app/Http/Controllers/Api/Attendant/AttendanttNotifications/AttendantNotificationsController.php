<?php

namespace App\Http\Controllers\Api\Attendant\AttendanttNotifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    NotificationAttendant,
    NotificationParant,
    Student,
    NotificationSchool,
};
use App\Repositories\Attendant\AttendanttNotifications\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Validator;


class AttendantNotificationsController extends Controller
{
    private $attendantNotificationsInterface;
    /**
     * Create a new controller instance.
     *
     * @param  NotificationRepositoryInterface $attendantNotificationsInterface
     * @return void
     */
    public function __construct(NotificationRepositoryInterface $attendantNotificationsInterface)
    {
        $this->attendantNotificationsInterface = $attendantNotificationsInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Get all parent firebase tokens
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allParentFirebaseTokens(Request $request)
    {

        try {

            $allFirebaseTokens = $this->attendantNotificationsInterface->getAllParentFirebaseTokens($request);
            return JSON($allFirebaseTokens);
        } catch (\Exception $exception) {

            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Get all parent firebase tokens for a student
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $student_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function parentFirebaseTokens(Request $request, $student_id)
    {
        try {

            $student = Student::where('id', $student_id)->where('school_id', $request->user()->school_id);

            if ($student->exists()) {
                $firebase_token = $student->first()->my_Parents->pluck('firebase_token')->toArray();
                // Now, $my__parent_ids contains an array of 'firebase_token' values
                return JSON($firebase_token);
            }
            return JSONerror('student does not exist');
        } catch (\Exception $exception) {

            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Store a newly created notification in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSchoolNotification(Request $request)
    {
        try {
            $data = $request->all();
            $data['school_id'] = $request->user()->school_id;
            $data['from'] = 'attendant:' . $request->user()->name;

            $validator = Validator::make($data, [
                'title' => 'required|string|min:2',
                'body' => 'required|string|min:2',
                'route' => 'required|string|min:1',
            ]);
            if ($validator->fails()) {
                return JSONerror($validator->errors());
            }
            $NotificationParant = NotificationSchool::create($data);

            return JSON($NotificationParant);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    /**
     * Summary of storeParentNotificationByStudent
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeParentNotification(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['parent_id'] = $id;
            $data['from'] = 'Attendant:' . $request->user()->name;
            $validator = Validator::make($data, [
                'title' => 'required|string|min:2',
                'body' => 'required|string|min:2',
                'route' => 'required|string|min:1',
                'parent_id' => ['required', 'exists:my__parents,id'],
            ]);
            if ($validator->fails()) {
                return JSONerror($validator->errors());
            }

            NotificationParant::create($data);
            return JSON('succes send message for all attendents');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    /**
     * Summary of storeAttendantNotification
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeAttendantNotification(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['attendant_id'] = $id;
            $data['from'] = 'parent:' . $request->user()->name;
            $validator = Validator::make($data, [
                'title' => 'required|string|min:2',
                'body' => 'required|string|min:2',
                'route' => 'required|string|min:1',
                'attendant_id' => ['required', 'exists:attendants,id,school_id,' . $request->user()->school_id],
            ]);
            if ($validator->fails()) {
                return JSONerror($validator->errors());
            }

            NotificationAttendant::create($data);
            return JSON('succes send message for all attendents');
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }


    public function showAttendantNotification(Request $request)
    {
        try {
            $notification = $request->user()->notification()->orderBy('created_at', 'desc')->paginateLimit();

            return JSON($notification);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
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
    public function show($id)
    {
        //
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
