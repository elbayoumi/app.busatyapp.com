<?php

namespace App\Traits\Schools;

use App\Models\Bus;
use App\Models\Classroom;
use App\Models\FixedTrip;
use App\Models\Grade;
use App\Models\SchoolGrade;
use App\Models\Student;

trait CreateTrait
{
    private function processCreateDefaultClassRooms($school, $lang_default_classrooms = 'default_classrooms_ar')
    {
        $this->getStoreFixedTrip($school);
        $grade_id = null;
        $classroom_id = null;
        // Chunk grades to manage memory usage
        Grade::orderBy('order', 'desc')->chunk(100, function ($grades) use ($school, $lang_default_classrooms, $classroom_id, $grade_id) {
            $bus = $this->getStorebus($school);

            foreach ($grades as $grade) {
                SchoolGrade::create([
                    'grade_id' => $grade->id,
                    'school_id' => $school->id,
                ]);
                $classrooms = json_decode($grade->{$lang_default_classrooms}, true);
                if (is_array($classrooms)) {
                    foreach (array_reverse($classrooms) as $grd) {
                        $classroom = Classroom::create([
                            'name' => $grd,
                            'grade_id' => $grade->id,
                            'school_id' => $school->id,

                        ]);
                        $classroom_id = $classroom->id;
                        $grade_id = $classroom->grade_id;
                    }
                }
            }

            // Assuming you want to process students for each chunk of grades
            // If you need to process students after all grades are processed, move this outside the chunk closure
            $firstGrade = $grades->first(); // Get the first grade from the chunk
            if ($firstGrade) {
                $this->storeStudents($school, null, $classroom_id, $grade_id, 'student ' . json_decode($firstGrade->default_classrooms)[0]);
            }
        });
    }
    public function getStoreFixedTrip($user)
    {

        $fixedTrip  = new FixedTrip();
        $fixedTrip->type                      = 'end_day';
        $fixedTrip->time_start                = '13:00:00';
        $fixedTrip->time_end                = '15:00:00';
        $fixedTrip->school_id                 = $user->id;
        $fixedTrip->save();

        $fixedTripStart  = new FixedTrip();
        $fixedTripStart->type                      = 'start_day';
        $fixedTripStart->school_id                 = $user->id;
        $fixedTripStart->save();

        return $fixedTrip;
    }
    public function getStorebus($user)
    {

        $bus  = new Bus();
        $bus->name                      = 'bus 1';
        $bus->notes                     = 'details';
        $bus->car_number                = rand(1020, 178500);
        $bus->school_id                 = $user->id;
        $bus->save();
        return $bus;
    }
    private function storeStudents($user, $bus_id, $classroom_id, $grade_id, $studentName)
    {
        $student                   = new Student();
        $student->name             = $studentName;
        $student->grade_id         = $grade_id;
        $student->school_id        = $user->id;
        $student->classroom_id     = $classroom_id;
        // $student->trip_type     = 'full_day';
        // $student->address          = $user->address;
        $student->bus_id          = $bus_id;
        $student->parent_key = randKey();
        $student->parent_secret = randKey();
        $student->save();
    }
}
