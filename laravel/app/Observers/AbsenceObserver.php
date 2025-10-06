<?php

namespace App\Observers;

use App\Models\Absence;
use App\Models\Attendant;
use App\Models\Notification;
use App\Services\Firebase\FcmService;
use App\Services\Schools\SchoolNotificationService;

class AbsenceObserver
{
    /**
     * Handle the Absence "created" event.
     *
     * @param  \App\Models\Absence  $absence
     * @return void
     */
    public function created(Absence $absence)
    {
        if ('attendants' != request()->user()->typeAuth) {
            // $absence->bus()->attendants()->fcmTokens->pluck('fcm_token')->toArray();
            $absence = $absence->load('bus.attendants.fcmTokens', 'students' , 'school');
            $student = $absence->students;
            // استخراج فقط fcm_token من كل سجل
            $fcmTokens = $absence->bus->attendants->pluck('fcmTokens.*.fcm_token')->flatten()->toArray();

            $attendants = $absence->bus->attendants;

            $attendantIds = $attendants->pluck('id')->flatten()->toArray();

            $fcmService = new FcmService;
            $title = 'غياب طالب';
            $body = "الطالب {$student->name} سجل غياب من ولي أمره يوم {$absence->attendence_date} في رحلة {$absence->attendence_type}";

            if (count($fcmTokens)) {
                $fcmService->sendNotification($fcmTokens, $title, $body);
            }

            $notificationModel = Notification::create([
                'data' => [
                    'from' => 'parent',
                    'title' => $title,
                    'body' => $body,
                    'type' => 'no-tracking',
                    'additional' => [],

                ],
            ]);

            // $topic = Topic::where('name' , $topic)->first();
            // $userModel=$attendants->getMorphClass();
            $userModel = get_class(new Attendant());

            $userModelInstance = app($userModel);

            // $modelIds = $topic->users($userModelInstance)->pluck("{$userModelInstance->getTable()}.id");
            $notificationModel->users($userModel)->attach($attendantIds);

            // $notificationModel->topics()->attach($topic->id);

            /**
             * sending notification to the school 
             * that the parent make his child absent 
             */ 

            $school = $absence->school;

            if ($school->student_absence_notification_status == 1) {

              $schoolNotificationService = new SchoolNotificationService(
                $school,
              );

              $schoolNotificationService->sendMulticastNotification(
                $title,
                $body
              );
    }
        }
    }

    /**
     * Handle the Absence "updated" event.
     *
     * @param  \App\Models\Absence  $absence
     * @return void
     */
    public function updated(Absence $absence)
    {
        //
    }

    /**
     * Handle the Absence "deleted" event.
     *
     * @param  \App\Models\Absence  $absence
     * @return void
     */
    public function deleted(Absence $absence)
    {
        //
    }

    /**
     * Handle the Absence "restored" event.
     *
     * @param  \App\Models\Absence  $absence
     * @return void
     */
    public function restored(Absence $absence)
    {
        //
    }

    /**
     * Handle the Absence "force deleted" event.
     *
     * @param  \App\Models\Absence  $absence
     * @return void
     */
    public function forceDeleted(Absence $absence)
    {
        //
    }
}
