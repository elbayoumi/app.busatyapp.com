<?php

namespace App\Http\Controllers\Api\schools\schoolMessages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\My_Parent;
use App\Models\School_messages;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class SchoolMessagesController extends Controller
{

    public function getAll(Request $request)
    {

        try {
            $messages = School_messages::where('school_id', $request->user()->id)
            ->with([
            'schools',
            'parents',

            ])
            ->orderBy('id', 'desc')
            ->paginateLimit();

            if ($messages->count() > 0) {
                return JSON( [
                    'messages' => $messages,
                    'messages_count' => $messages->count(),
                ]);
            }
            return response()->json([
                'message' => __("Message not found"),
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }


    public function school_messages()
    {
        return $this->belongsTo(School_messages::class,'school_messages_id');
    }
    public function parents()
    {
        return $this->belongsTo(My_Parent::class,'my__parent_school_message');
    }

    public function getShow(Request $request, $id)
    {

        $message = School_messages::where('id', $id)->first();

        $parents_ids = DB::table('my__parent_school_message')->where('school_messages_id', $message->id)->pluck('my__parent_id');

        $student_ids = DB::table('my__parent_student')->whereIn('my__parent_id', $parents_ids)->pluck('student_id');

        $students_message = Student::where('school_id', $message->school_id)->whereIn('id', $student_ids)->orderBy('id', 'desc')->get();
        $students_no_message = Student::where('school_id', $message->school_id)->whereNotIn('id', $student_ids)->orderBy('id', 'desc')->get();
        try {
            $messages = School_messages::where('school_id', $request->user()->id)->where('id', $id)
            ->with([
                'schools',
                'parents',
            ]);

            if ($messages != null) {
                return response()->json([
                    'data' => [
                        'messages' => $messages,
                        'students_message' => $students_message,
                        'students_no_message' => $students_no_message,

                    ],$messages,
                    'message' => __("success message"),
                    'status' => true
                ]);
            }
            return response()->json([
                'message' => __("Message not found"),
                'status' => false,
            ], 404);
        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }




    public function getStore(Request $r)
    {

        try {
            $validator = Validator::make($r->all(), [
                'name' => ['required', 'string', 'min:1', 'max:100'],
                'message' => ['required', 'string', 'min:1', 'max:255'],
                'event_date' => 'required|date|date_format:Y-m-d',
            ]);


            if ($validator->fails()) {
                return response()->json(['errors' => true, 'messages' => $validator->errors()], 403);
            }

            if ($r->event_date < date('Y-m-d')) {

                return response()->json(['errors' => true, 'message' => 'يجب ان يكون تاريخ الحدث  بعد تاريخ اليوم'], 403);

            }
            $student_ids = Student::where('school_id', $r->user()->id)->pluck('id');
            $parent_ids = DB::table('my__parent_student')->whereIn('student_id', $student_ids)->pluck('my__parent_id');
            $parents = My_Parent::whereIn('id', $parent_ids)->orderBy('id', 'desc')->get();


                $message = School_messages::create([

                    'name' => $r->name,
                    'school_id' => $r->user()->id,
                    'message' => $r->message,
                    'event_date' => $r->event_date,

                ]);

                foreach($parents as $parent) {

                    $message->parents()->attach($parent);
                }


                return response()->json(['errors' => false, 'message' => __("message added successfully")], 200);

        } catch (\Exception $exception) {
            return JSONerror($exception->getMessage(), 500);

        }
    }







}
