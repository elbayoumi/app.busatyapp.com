<?php

namespace App\Repositories\Attendant\AttendanttNotifications;
use Illuminate\Http\Request;

interface NotificationRepositoryInterface
{
    public function getAllParentFirebaseTokens(Request $request) :array;
    public function getParentFirebaseTokens(Request $request, $student_id);
    public function storeSchoolNotification(Request $request);
    public function storeParentNotification(Request $request, $id);
    public function storeAttendantNotification(Request $request, $id);
    public function getAttendantNotifications(Request $request);
}
