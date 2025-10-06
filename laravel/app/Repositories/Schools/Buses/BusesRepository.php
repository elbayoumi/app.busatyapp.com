<?php

namespace App\Repositories\Schools\Buses;

use App\Models\{
    Student,
};


use App\Http\Requests\StoreStudentsToBusRequest;

class BusesRepository implements BusesInterface
{

    public function storeStudentsToBus(StoreStudentsToBusRequest $r)
    {
            $validated = $r->validated();
            $schoolId = $r->user()->id;

            // Determine the values to update
            $updateValues = [
                'bus_id' => $validated['bus_id'],
                'trip_type' => $validated['bus_id'] ? $validated['trip_type'] : null,
            ];

            // Perform a bulk update
            Student::where('school_id', $schoolId)
                ->whereIn('id', $validated['student_id'])
                ->update($updateValues);

            // Fetch updated students to return in response
            $updatedStudents = Student::where('school_id', $schoolId)
                ->whereIn('id', $validated['student_id'])
                ->get();

            return $updatedStudents;

    }
}
