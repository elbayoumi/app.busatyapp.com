<?php

namespace App\Http\Controllers\Api\parents\attendantsMessages;

use App\Http\Controllers\Controller;
use App\Models\{

    AttendantParentMessage,

    My_Parent,

};



use Illuminate\{
    Http\Request,
};

class AttendantsMessagesController extends Controller
{

    public function getAll(Request $request)
    {


        try {

            $arrIdStudent = $this->getChildren($request);

            $userMessages = AttendantParentMessage::whereIn('student_id', $arrIdStudent)->with(['bus.schools','student'])->orderBy('created_at', 'desc');
            $userMessagesCount=$userMessages->count();
            $userMessagesStatusCount=$userMessages->where('status', 0)->count();
            if ($userMessages->count() > 0) {
                return JSON([
                    'userMessages' => $userMessages->paginateLimit(),
                    'userMessages_count' => $userMessagesCount,
                    'userMessages_new_count' => $userMessagesStatusCount,
                ]);
            }
            return response()->json([
                'message' => __('user Messages not found'),
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);
        }
    }


    public function getNew(Request $request)
    {
        try {
            $arrIdStudent = $this->getChildren($request);

            $userMessagesNew = AttendantParentMessage::whereIn('student_id', $arrIdStudent)->where('status', 0)
                ->with([
                    'attendant',
                    'bus.schools','student'
                ])
                ->orderBy('id', 'desc')
                ->paginateLimit();

            if ($userMessagesNew->count() > 0) {
                return response()->json([
                    'data' => [
                        'userMessagesNew' => $userMessagesNew,
                        'userMessages_count' => $userMessagesNew->count(),

                    ],
                    'message' => __("success message"),
                    'status' => true
                ],200);
            }
            return response()->json([
                'message' => __('No new messages'),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);
        }
    }


    public function getShow(Request $request, $id)
    {
        try {
            $messages = AttendantParentMessage::whereId($id)
                ->with([
                    'bus',
                    'attendant',
                ]);

            switch ($messages->exists()) {
                case true:
                    $message = $messages->first();
                    $message->status = 1;
                    $message->save();
                    return response()->json([
                        'data' => $message,
                        'message' => __("success message"),
                        'status' => true
                    ], 200);
            }
            return response()->json([
                'message' => __('No new messages'),
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(),500);

        }
    }

    private function getChildren($request)
    {
        try{
        $students = My_Parent::where('id', $request->user()->id)->whereHas('students', function ($q) {
            $q->select('student_id');
        })->first()['students'];
        $arr = [];
        foreach ($students as $student) {
            array_push($arr, $student['pivot']->student_id);
        }
        return $arr;
    }catch(\Exception $e) {
        return JSONerror($e->getMessage(),500);

    }
    }
}
