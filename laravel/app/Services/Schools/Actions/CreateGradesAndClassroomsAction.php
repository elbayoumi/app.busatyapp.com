<?php

namespace App\Services\Schools\Actions;

use App\Models\Bus;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\School;
use App\Models\SchoolGrade;
use App\Models\Student;

class CreateGradesAndClassroomsAction
{
    /**
     * Create default grades, classrooms, and one student for each grade.
     */
    public static function run(School $school, Bus $bus): void
    {
        $langKey = 'classrooms_' . lang();
        $studentCreated = false; // Track whether a student has already been created

        Grade::orderBy('order', 'desc')->chunk(100, function ($grades) use ($school, $langKey, $bus, &$studentCreated) {
            foreach ($grades as $grade) {
                // Attach grade to the school
                SchoolGrade::create([
                    'grade_id' => $grade->id,
                    'school_id' => $school->id,
                ]);

                // Decode classroom names from the localized JSON field
                $classrooms = json_decode($grade->{$langKey}, true);

                if (is_array($classrooms)) {
                    // Loop through classrooms in reverse order
                    foreach (array_reverse($classrooms) as $name) {
                        // Create the classroom
                        $classroom = Classroom::create([
                            'name' => $name,
                            'grade_id' => $grade->id,
                            'school_id' => $school->id,
                        ]);

                        // Only create one student for the first created classroom
                        if (!$studentCreated) {
                            self::createStudent($school, $bus, $classroom, $grade, $name);
                            $studentCreated = true;
                        }
                    }
                }
            }
        });
    }


    /**
     * Create a default student for a classroom and grade.
     */
    private static function createStudent(School $school, Bus $bus, Classroom $classroom, Grade $grade, string $name): void
    {
        Student::create([
            'name' => 'student ' . $name,
            'grade_id' => $grade->id,
            'school_id' => $school->id,
            'classroom_id' => $classroom->id,
            'bus_id' => $bus->id,
            'parent_key' => randKey(),
            'parent_secret' => randKey(),
        ]);
    }
}
