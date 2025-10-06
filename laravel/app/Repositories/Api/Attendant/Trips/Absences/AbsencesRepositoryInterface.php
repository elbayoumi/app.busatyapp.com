<?php

namespace App\Repositories\Api\Attendant\Trips\Absences;

interface AbsencesRepositoryInterface
{
    public function getAbsences(int $attendant_id, int $school_id): array;
    public function storeAbsence(array $data, int $student_id): bool;
    public function create(array $data): bool;
}
