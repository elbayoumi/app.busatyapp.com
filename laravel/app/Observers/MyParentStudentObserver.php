<?php

namespace App\Observers;

use App\Models\My__parent_student;
use App\Models\Notification as NotificationModel;
use App\Services\Firebase\FcmService;
use App\Services\StudentService;

class MyParentStudentObserver
{
    protected StudentService $StudentService;
    function __construct()
    {
        $this->StudentService = new StudentService;

    }

    /**
     * Handle the My__parent_student "created" event.
     *
     * @param  \App\Models\My__parent_student  $my__parent_student
     * @return void
     */
    public function created(My__parent_student $my__parent_student)
    {
        // $fcm_token=$my__parent_student->my_parent->fcmTokens()->pluck('fcm_token')->toArray();
        // // $my__parent_student->student()
        // $topic='student'.$my__parent_student->student_id ;
        // $fcmService=new FcmService;
        // $fcmService->subscribeToTopic($topic, $fcm_token);

        $this->StudentService->my__parent_studentFcmRegister($my__parent_student);

    }

    /**
     * Handle the My__parent_student "updated" event.
     *
     * @param  \App\Models\My__parent_student  $my__parent_student
     * @return void
     */
    public function updated(My__parent_student $my__parent_student)
    {
        //
    }

    /**
     * Handle the My__parent_student "deleted" event.
     *
     * @param  \App\Models\My__parent_student  $my__parent_student
     * @return void
     */
    public function deleted(My__parent_student $my__parent_student)
    {
        // $fcm_token=$my__parent_student->my_parent->fcmTokens()->pluck('fcm_token')->toArray();
        // // $my__parent_student->student()
        // $topic='student'.$my__parent_student->student_id ;

        // $fcmService=new FcmService;

        // $fcmService->unsubscribeFromTopic($topic, $fcm_token);
        $this->StudentService->my__parent_studentFcmUnRegister($my__parent_student);
        // $school_id=$my__parent_student->student->school_id;
        // $my_parent=$my__parent_student->my_parent;

        // if (!is_null($coupon->school_id)) {
        //     $schoolCond = $usr->students()->where('school_id', $coupon->school_id)->exists();
        //     if (!$schoolCond) {
        //         return JSONerror(__('This coupon code is not valid for your school'));
        //     }
        // }

    }

    /**
     * Handle the My__parent_student "restored" event.
     *
     * @param  \App\Models\My__parent_student  $my__parent_student
     * @return void
     */
    public function restored(My__parent_student $my__parent_student)
    {
        //
    }

    /**
     * Handle the My__parent_student "force deleted" event.
     *
     * @param  \App\Models\My__parent_student  $my__parent_student
     * @return void
     */
    public function forceDeleted(My__parent_student $my__parent_student)
    {
        //
    }
}
