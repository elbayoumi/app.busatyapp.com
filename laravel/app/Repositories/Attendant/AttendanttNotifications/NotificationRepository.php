<?php

namespace App\Repositories\Attendant\AttendanttNotifications;

use App\Models\{
    NotificationAttendant,
    NotificationParant,
    Student,
    NotificationSchool
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getAllParentFirebaseTokens(Request $request) :array
    {
        $students = Student::where('bus_id', $request->user()->bus_id)->get();
        // return $students->toArray() ;
        $allFirebaseTokens = [];

        foreach ($students as $student) {
            $firebaseTokens = $student->my_Parents->whereNotNull('firebase_token')->pluck('firebase_token')->toArray();
            $allFirebaseTokens = array_merge($allFirebaseTokens, $firebaseTokens);
            $allFirebaseTokens=array_unique(array_filter($allFirebaseTokens));

        }

        return $allFirebaseTokens;
    }

    public function getParentFirebaseTokens(Request $request, $student_id)
    {
        $student = Student::where('id', $student_id)
            ->where('school_id', $request->user()->school_id)
            ->first();

        if ($student) {
            return $student->my_Parents->pluck('firebase_token')->toArray();
        }

        return null;
    }

    public function storeSchoolNotification(Request $request)
    {
        $data = $request->all();
        $data['school_id'] = $request->user()->school_id;
        $data['from'] = 'attendant:' . $request->user()->name;

        $validator = Validator::make($data, [
            'title' => 'required|string|min:2',
            'body' => 'required|string|min:2',
            'route' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return NotificationSchool::create($data);
    }

    public function storeParentNotification(Request $request, $id)
    {
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
            return $validator->errors();
        }

        NotificationParant::create($data);
        return 'success send message for all attendants';
    }

    public function storeAttendantNotification(Request $request, $id)
    {
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
            return $validator->errors();
        }

        NotificationAttendant::create($data);
        return 'success send message for all attendants';
    }

    public function getAttendantNotifications(Request $request)
    {
        return $request->user()->notification()->orderBy('created_at', 'desc')->paginate(10);
    }
}
