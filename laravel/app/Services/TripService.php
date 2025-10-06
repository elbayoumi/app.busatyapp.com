<?php

namespace App\Services;

use App\Models\My_Parent;
use App\Models\Topic;
use App\Repositories\Api\Student\StudentRepositoryInterface;
use App\Services\Firebase\FcmService;
use App\Notifications\Notify;
use Illuminate\Support\Facades\Notification;
use App\Models\Absence;
use App\Models\Trip;
use App\Services\Schools\SchoolNotificationService;

class TripService
{
    protected StudentRepositoryInterface $studentRepository;
    protected FcmService $fcmService;

    public function __construct()
    {
        $this->studentRepository = app(StudentRepositoryInterface::class);
        $this->fcmService = new FcmService;
    }

    public function open($trip, $userType = null)
    {
        $topicName = $trip->customIdentifier->identifier;

        // Fetch FCM tokens for parents related to the trip
        $parents = $this->studentRepository->tripFcm($trip);
        $this->fcmService->subscribeToTopic($topicName, $parents['tokens']);

        $userType = $userType ?: My_Parent::class;

        // Load the school
        $trip = $trip->load('school');
        $school = $trip->school;

        // Prepare notification
        $title = [
            'start_day' => 'صباح الخير',
            'end_day' => "مساء الخير"
        ];
        $body = "باص مدرسة $school->name بدأ الرحلة وفي طريقه اليك";

        $additional = [
            'trip_id' => $trip->id,
        ];
        $type = 'tracking';

        // Create or retrieve the topic
        $topic = Topic::firstOrCreate(['name' => $topicName]);
        $topic->users($userType)->attach($parents['ids']);

        // Send notification
        Notification::send($trip, new Notify(
            $title[$trip->trip_type->value],
            $body,
            $topicName,
            $userType,
            $type,
            $additional
        ));

        return $parents['tokens'];
    }

    public function endTrip($trip, $userType = null)
    {
        $topicName = $trip->customIdentifier->identifier;
        $userType = $userType ?: My_Parent::class;

        $trip = $trip->load('school');
        $school = $trip->school;

        $title = [
            'start_day' => 'صباح الخير',
            'end_day' => "مساء الخير"
        ];
        $body = "باص مدرسة {$school->name} وصل للمدرسة";
        $type = 'no-tracking';

        Notification::send($trip, new Notify(
            $title[$trip->trip_type->value],
            $body,
            $topicName,
            $userType,
            $type
        ));

        Topic::where('name', $topicName)->delete();
    }

    public function send($trip, $title, $body, $type, $userType = null)
    {
        $userType = $userType ?: My_Parent::class;
        $topicName = $trip->customIdentifier->identifier;

        Notification::send($trip, new Notify(
            $title,
            $body,
            $topicName,
            $userType,
            $type
        ));
    }

    public function handleTripCreated(Trip $trip): void
    {
        // Attach students (absent/present) to trip
        $this->attachStudentsToTrip($trip);

        // Attach attendants
        $trip->attendants()->attach($trip->bus->attendants()->pluck('id')->toArray());

        // Notify school
        $this->sendTripStartedNotification($trip);
    }

    protected function attachStudentsToTrip(Trip $trip): void
    {
        $exceptedStudentType = $trip->trip_type == "start_day" ? "end_day" : "start_day";

        // Get absent students
        $absentStudentIds = Absence::where("bus_id", $trip->bus_id)
            ->where("attendence_date", $trip->trips_date)
            ->where("attendence_type", "!=", $exceptedStudentType)
            ->pluck("Student_id")
            ->toArray();

        $absentStudentsPivot = $this->preparePivotData($absentStudentIds, 'absent');

        // Get present students
        $presentStudentIds = $trip->bus->students()
            ->where("trip_type", "!=", $exceptedStudentType)
            ->whereNotIn("id", $absentStudentIds)
            ->pluck("id")
            ->toArray();

        $presentStudentsPivot = $this->preparePivotData($presentStudentIds, 'waiting');

        // Attach all students
        $trip->students()->attach($absentStudentsPivot + $presentStudentsPivot);
    }

    protected function preparePivotData(array $studentIds, string $status): array
    {
        return collect($studentIds)->mapWithKeys(fn($id) => [
            $id => [
                'status' => $status,
                'onboard_at' => null,
                'arrived_at' => null,
            ]
        ])->toArray();
    }

    public function handleTripUpdated(Trip $trip, array $changes): void
    {
        if ((isset($changes['status']) && $changes['status'] == 1) ||
            (isset($changes['end_at']) && $changes['end_at'] != null)) {
            $this->sendTripFinishedNotification($trip);
        }
    }

    protected function sendTripStartedNotification(Trip $trip): void
    {
        $trip->load('bus.school');
        $school = $trip->bus->school;

        if ($school->trip_start_end_notification_status == 1) {
            $title = __('Trip started for bus') . " {$trip->bus->name}";
            $body = __('The bus') . " {$trip->bus->name} " . __('has started its trip now');

            $schoolNotificationService = new SchoolNotificationService($school);
            $schoolNotificationService->sendMulticastNotification($title, $body);
        }
    }

    /**
     * Sends a notification to the school when a trip has finished.
     *
     * This function loads the associated bus and school for the given trip,
     * checks the school's notification settings, and sends a multicast
     * notification to the school if the trip start/end notification status
     * is enabled. The notification includes the bus name and a message
     * indicating that the trip has finished.
     *
     * @param Trip $trip The trip that has finished.
     * @return void
     */

    protected function sendTripFinishedNotification(Trip $trip): void
    {
        $trip->load('bus.school');
        $school = $trip->bus->school;

        if ($school->trip_start_end_notification_status == 1) {
            $title = __('Trip finished for bus') . " {$trip->bus->name}";
            $body = __('The bus') . " {$trip->bus->name} " . __('has finished its trip now');

            $schoolNotificationService = new SchoolNotificationService($school);
            $schoolNotificationService->sendMulticastNotification($title, $body);
        }
    }
}
