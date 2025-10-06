<?php

namespace App\Repositories\Api\Student;

use App\Models\Student;
use App\Models\Trip;

interface StudentRepositoryInterface
{
    public function parentsFcm(Student $student): array;
    public function tripFcm(Trip $trip): array;
    public function studentFcm(Student $students): array;

}
