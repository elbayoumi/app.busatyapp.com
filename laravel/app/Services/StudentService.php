<?php

namespace App\Services;

use App\Models\My_Parent;
use App\Models\Topic;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use App\Services\Firebase\FcmService;
use App\Notifications\Notify;
use Illuminate\Support\Facades\Notification;

class StudentService
{
    protected StudentRepositoryInterface $studentRepository;
    protected FcmService $fcmService;

    public function __construct()
    {
        // Initialize the student repository and FCM service
        $this->studentRepository = app(StudentRepositoryInterface::class);
        $this->fcmService = new FcmService;
    }

    public function my__parent_studentFcmRegister($my__parent_student)
{

    $fcm_token=$my__parent_student->my_parent->fcmTokens()->pluck('fcm_token')->toArray();
    // $topic='student'.$my__parent_student->student ;
    $student=$my__parent_student->student;
    $topic= $student->customIdentifier->identifier;
    $topic=Topic::where('name',$topic)->first();
    $userType =  My_Parent::class;
    // $topic->users($userType)->attach([$my__parent_student->my__parent_id]);
    $topic->users($userType)->syncWithoutDetaching([$my__parent_student->my__parent_id]);


    $this->fcmService->subscribeToTopic($topic->name, $fcm_token);
}
    /**
     * When a parent logs in, subscribe all of their students to the relevant topics so that they can receive notifications.
     * @param My_Parent $my_parent
     */
// public function myParentFcmLogin($my_parent,$fcm_token)
// {
//     // $fcm_token=$my_parent->fcmTokens()->pluck('fcm_token')->toArray();
//     // $student()
//     // $p=My_Parent::first()->topics()->get();

//     $topics = $my_parent->topics()->pluck('name')->toArray();
//     $this->fcmService->subscribeTokensToTopics($topics, [$fcm_token]);

//     // foreach($topics as $topic){
//     //     $this->fcmService->subscribeToTopic($topic->name, $fcm_token);
//     // }
// }

public function my__parent_studentFcmUnRegister($my__parent_student)
{
    $fcm_token=$my__parent_student->my_parent->fcmTokens()->pluck('fcm_token')->toArray();
    // $my__parent_student->student()

    $topic='student'.$my__parent_student->student_id ;
    $this->fcmService->unsubscribeFromTopic($topic, $fcm_token);
}
//     public function parentFcmRegister($my_parent,array $my_student_ids)
// {
//     $fcm_token=-$my_parent->fcmTokens()->pluck('fcm_token')->toArray();
//     // $my__parent_student->student()

//     $topic='student'.$my__parent_student->student_id ;
//     $this->fcmService->subscribeToTopic($topic, $fcm_token);
// }
    public function send($student, $title, $body, $type, $userType = null)
    {
        // Ensure $user defaults to the authenticated user if not provided
        $userType = $userType ?: My_Parent::class;

        // Create a topic name based on the student ID
        $topic='student'.$student->student_id ;

        // Send the notification
        Notification::send($student, new Notify($title, $body, $topic, $userType, $type));
    }

}
