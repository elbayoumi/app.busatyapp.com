<?php

namespace App\Repositories\Api\Attendant\Trips\Absences;

use App\Models\Absence;
use App\Models\Student;

class AbsencesRepository implements AbsencesRepositoryInterface
{
    public function getAbsences(int $attendant_id, int $school_id): array
    {
        // منطق جلب الغيابات
        return Absence::where('attendant_id', $attendant_id)
            ->where('school_id', $school_id)
            ->get()
            ->toArray();
    }

    public function storeAbsence(array $data, int $student_id): bool
    {
        // منطق تخزين غياب
        $absence = new Absence();
        $absence->fill($data);
        $absence->student_id = $student_id;

        return $absence->save();
    }

    public function create(array $data): bool
    {
        // منطق إنشاء غياب
        $absence = new Absence();
        $absence->fill($data);

        return $absence->save();
    }
}
