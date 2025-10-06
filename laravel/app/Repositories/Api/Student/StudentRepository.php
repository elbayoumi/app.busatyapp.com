<?php

namespace App\Repositories\Api\Student;

use App\Models\Attendant;
use App\Models\Student;
use App\Models\Trip;

class StudentRepository implements StudentRepositoryInterface
{
    public function parentsFcm(Student $student): array
    {
        $student->load('my_Parents.fcmTokens');

        // dd($student);
        if ($student->my_Parents) {
            $filteredTokens = $student->my_Parents
                ->pluck('fcmTokens')
                ->filter()
                ->unique()
                // ->values() // استخدام values هنا لإعادة تعيين المفاتيح
                ->toArray();

            return $filteredTokens;
        }

        return [];
    }
    // public function tripFcm(Trip $trip): array
    // {
    //     $trip = $trip->load('bus.students.my_Parents');

    //     // Apply the 'waiting' scope on the students
    //     $students = $trip->bus->students->filter(function($student) use ($trip) {
    //         return $student->waiting($trip->attendance_type);
    //     });

    //     // Collect Firebase tokens
    //     $fireBaseTokens = [];

    //     foreach ($students as $student) {
    //         foreach ($student->my_Parents as $parent) {
    //             $fireBaseTokens[] = $parent->fcmTokens;
    //         }
    //     }

    //     return $fireBaseTokens = array_unique(array_filter($fireBaseTokens));

    // }
    // public function tripFcm(Trip $trip): array
    // {
    //     // Load relationships: bus, students, and their parents
    //     $trip->load('bus.students.my_Parents');

    //     // Filter students based on the 'waiting' scope
    //     $students = $trip->bus->students->filter(function ($student) use ($trip) {
    //         return $student->waiting($trip->attendance_type);
    //     });

    //     // Collect Firebase tokens
    //     $fireBaseTokens = [];
    //     $parentIds = [];

    //     foreach ($students as $student) {
    //         foreach ($student->my_Parents as $parent) {
    //             // Ensure $parent->fcmTokens is an array; convert it if necessary
    //             $tokens =  $parent->fcmTokens->pluck('fcm_token')->toArray();
    //             $fireBaseTokens = array_merge($fireBaseTokens, $tokens);
    //              array_push($parentIds, $parent->id);
    //         }
    //     }
    //     // dd($fireBaseTokens);
    //     // Filter out empty values and remove duplicates
    //     return ['tokens' => array_values(array_unique(array_filter($fireBaseTokens))), 'ids' => $parentIds];
    // }
    public function tripFcm(Trip $trip): array
    {
        // Load relationships: bus, students, and their parents
        $trip->load('bus.students.my_Parents');

        // Filter students based on the 'waiting' scope
        $students = $trip->bus->students->filter(function ($student) use ($trip) {
            return $student->waiting($trip->attendance_type);
        });

        // Collect Firebase tokens
        $fireBaseTokens = [];
        $parentIds = [];

        foreach ($students as $student) {
            foreach ($student->my_Parents as $parent) {
                // Ensure $parent->fcmTokens is an array; convert it if necessary
                $tokens = $parent->fcmTokens->pluck('fcm_token')->toArray();
                $fireBaseTokens = array_merge($fireBaseTokens, $tokens);
                $parentIds[] = $parent->id; // Use array push

            }
            // $this->attendantParentMessage( $student,$trip->trip_type, $message_en, $message, $notifications_type, $title);

        }

        // Return an associative array with keys 'tokens' and 'ids'
        // return array_values(array_unique(array_filter($fireBaseTokens)));
        return [
            'tokens' => array_values(array_unique(array_filter($fireBaseTokens))), // Ensure unique and filtered tokens
            'ids' => array_values(array_unique(array_filter($parentIds))), // Ensure unique and filtered parent IDs
            'students' => $students->pluck('id')->toArray() // Collect student IDs
        ];
    }
    public function studentFcm(Student $student): array
    {
        // Load relationships: bus, students, and their parents

        // Collect Firebase tokens
        $fireBaseTokens = [];
        $parentIds = [];

            foreach ($student->my_Parents as $parent) {
                // Ensure $parent->fcmTokens is an array; convert it if necessary
                $tokens = $parent->fcmTokens->pluck('fcm_token')->toArray();
                $fireBaseTokens = array_merge($fireBaseTokens, $tokens);
                $parentIds[] = $parent->id; // Use array push

            }
            // $this->attendantParentMessage( $student,'start_day', $message_en, $message, $notifications_type, $title);



        // Return an associative array with keys 'tokens' and 'ids'
        // return array_values(array_unique(array_filter($fireBaseTokens)));
        return [
            'tokens' => array_values(array_unique(array_filter($fireBaseTokens))), // Ensure unique and filtered tokens
            'ids' => array_values(array_unique(array_filter($parentIds))) // Ensure unique and filtered parent IDs
        ];
    }
    private function attendantParentMessage(Student $student,$trip_type, $message_en, $message, $notifications_type, $title)
{
    $attendant = Attendant::where('bus_id', $student->bus_id)->first();

    $student->attendantParentMessage()->create([
        'attendant_id' => $attendant->id, // على سبيل المثال
        'message' => $message,
        'message_en' => $message_en,
        'title' => $title,
        'notifications_type' => $notifications_type,
        'trip_type' =>  $trip_type,
        'bus_id' =>  $student->bus_id, // رقم الباص
    ]);
}
}
