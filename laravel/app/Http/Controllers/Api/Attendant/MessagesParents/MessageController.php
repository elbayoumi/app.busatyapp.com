<?php

namespace App\Http\Controllers\Api\Attendant\MessagesParents;

use TripFacadeService;

use App\Enum\TripTypebBusEnum;
use App\Http\Controllers\Controller;
use App\Models\{
    Attendance,
    Attendant,
    AttendantParentMessage,
    School,
    My_Parent,
    NotificationText,
    School_messages,
    Static_messages,
    Student,
    Trip,
};
use App\Notifications\Notify;
use App\Notifications\StudentNotification;
use App\Notifications\TripNotification;
use Carbon\Carbon;
use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
    Support\Facades\DB,
};
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{

    public function messageStatic()
    {
        $static_messages = Static_messages::select('id', 'message')->get();
        return JSON($static_messages);
    }
    public function getCreateMessageStartDay(Request $r)
    {
        return $this->create_message($r, 'start_day');
    }
    public function getCreateMessageEndDay(Request $r)
    {
        return $this->create_message($r, 'end_day');
    }
    private function create_message($r, $trip_type)
    {
        try {
            $static_messages = Static_messages::where('trip_type', $trip_type)->orWhereNull('trip_type')->where('school_id', $r->user()->school_id)->orWhereNull('school_id')->get();

            if ($static_messages->count() > 0) {
                return response()->json([
                    'data' => ['static_messages' => $static_messages],
                    'message' => __('success message'),
                    'status' => true
                ]);
            }

            return response()->json([
                'message' => __('data not found'),
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }

    public function getStoreStartDay(Request $r, $student_id)
    {
        return $this->getStore($r, $student_id, 'start_day');
    }
    public function getStoreEndtDay(Request $r, $student_id)
    {
        return $this->getStore($r, $student_id, 'end_day');
    }
    public function getStoreStartDayForAll(Request $r)
    {

        return $this->getStoreForAll($r, 'start_day');
    }
    public function getStoreEndDayForAll(Request $r)
    {

        return $this->getStoreForAll($r, 'end_day');
    }
    private function getStore($r, $student_id, $trip_type)
    {
        try {

            $validation = Validator::make($r->all(), [
                'static_message_id'        =>  'required|exists:notification_texts,id',
            ]);

            if ($validation->fails()) {
                return response()->json(['errors' => true, 'messages' => $validation->errors()], 422);
            }
            $trip = Trip::where('trip_type', TripTypebBusEnum::from($trip_type)->value)->today()->where('bus_id', $r->user()->bus_id);
            if ($trip->exists()) {
                $trip = $trip->first();
            } else {
                return response()->json(['errors' => true, 'message' => __('The trip must start first')], 422);
            }

            $student =  Student::where('bus_id', $r->user()->bus_id)->where('id', $student_id)->first();

            if ($student != null) {
                // $parents = $student->my_Parents()->get();
                $notify = NotificationText::whereId($r->static_message_id)->first();
                // if ($parents->isNotEmpty()) {

                // AttendantParentMessage::create([
                //     'bus_id' => $r->user()->bus_id,
                //     'student_id' => $student->id,
                //     'attendant_id' => $r->user()->id,
                //     'message' =>  $messages->message,
                //     'title' =>  $student->name,
                //     'trip_type' => $trip_type,

                //     'trip_id' => $trip->id

                // ]);
                $classes = [
                    get_class($student) => $student,
                    get_class($student->schools()->getModel()) => $student->schools,
                ];
                // $originalText = "اسم الطالب هو {{Student}}->name";

                $body = textToClass($notify->body_tr, $classes);
                $title = $notify->title_tr;
                $body = $body;
                $notifications_type = $r?->notifications_type ?? 'no-tracking';
                // $topic = 'student' . $student->id;
                $topic= $student->customIdentifier->identifier;

                // Notification::send($student, new StudentNotification($title, $body, $notifications_type));
                Notification::send($student, new Notify($title, $body, $topic, $notify->to_model_type, $notifications_type));

                return response()->json(['errors' => false, 'message' => __('message added successfully')], 201);
            }
            return response()->json(['errors' => true, 'message' => __("Something was wrong")], 500);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
    private function getStoreForAll($r, $trip_type)
    {
        try {
            $user = $r->user();
            $validation = Validator::make($r->all(), [
                // 'trip_id'        =>  'required|exists:trips,id',
                'static_message_id'        =>  'required|exists:notification_texts,id',
            ]);
            $current_date = Carbon::now()->format('Y-m-d');

            // $validation = Validator::make($r->all(), [
            //     'message'        =>  'required|max:255,min:5',
            //     'message_en'        =>  'required|max:255,min:5',
            //     'notifications_type'        =>  'nullable|max:255,min:5',
            // ]);

            if ($validation->fails()) {
                return response()->json(['errors' => true, 'messages' => $validation->errors()], 200);
            }
            $trip = Trip::where('trip_type', TripTypebBusEnum::from($trip_type)->value)->where('bus_id', $r->user()->bus_id)->notCompleted();
            if ($trip->exists()) {
                $trip = $trip->first();
            } else {
                return response()->json(['errors' => true, 'message' => __('The journey must start first')], 200);
            }
            // $attendances=Attendance::where('trip_id',$trip->id)->pluck('student_id');

            $student =  Student::where('bus_id', $user->bus_id)->whereIn('trip_type', [$trip_type, 'full_day'])->whereDoesntHave('absences', function ($query) use ($trip_type, $current_date) {
                $query->where('attendence_date', $current_date)->whereIn('attendence_type', ['full_day', $trip_type]);
            });
            // return response()->json(['errors' => false, 'message' => $student->get()], 200);
            $classes = [
                get_class($user->schools()->getModel()) => $user->schools,
            ];
            $notify = NotificationText::whereId($r->static_message_id)->first();

            // $originalText = "اسم الطالب هو {{Student}}->name";
            $body = textToClass($notify->body_tr, $classes);
            $title = $notify->title_tr;
            $body = $body;
            $notifications_type = $r?->notifications_type ?? 'no-tracking';
            TripFacadeService::send($trip, $title, $body, $notifications_type);
            // Notification::send($trip, new TripNotification($title, $body,$notifications_type));

            return response()->json(['errors' => false, 'message' => __("message added successfully")], 200);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);
        }
    }
}
