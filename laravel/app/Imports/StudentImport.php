<?php

namespace App\Imports;

use App\Models\Bus;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $school;
    private $grade;
    private $classroom;
    private $errors = []; // To collect errors

    public function __construct($school = null, $grade = null, $classroom = null)
    {
        $this->school = $school;
        $this->grade = $grade;
        $this->classroom = $classroom;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'phone' => ['nullable',  'min:8', "max:20"],
            'gender' => ['nullable',  'in:0,1'],
            'address' => ['nullable',  'max:255', 'min:2'],
            'trip_type' => ['nullable', 'in:0,1,2'],
            'bus' => ['nullable',  'max:255', 'min:2'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Please enter the student name.',
            'name.min' => 'The student name must be at least 3 characters.',
            'name.max' => 'The student name cannot exceed 255 characters.',
            'gender.in' => 'The gender must be either "male" or "female".',
            'trip_type.in' => 'The trip type must be "full_day", "start_day", or "end_day".',
            'phone.regex' => 'The phone number must follow the correct format.',
        ];
    }

    public function collection(Collection $rows)
    {
        $tripTypes = [
            'full_day',
            'start_day',
            'end_day',
        ];

        try {
            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $lineNumber = $index + 2; // Adjust for header row

                // ✅ Skip empty rows
                if ($row->filter()->isEmpty()) {
                    continue;
                }

                // ✅ Check if name is missing
                if (!isset($row['name']) || empty(trim($row['name']))) {
                    $this->errors[] = "Row {$lineNumber}: Please enter the student name.";
                    continue;
                }

                try {
                    $student = new Student();
                    $student->name = $row['name'];
                    $student->phone = $row['phone'] ?? null;
                    $student->grade_id = $this->grade;
                    $student->latitude = $row['latitude'] ?? null;
                    $student->longitude = $row['longitude'] ?? null;
                    $student->address = $row['address'] ?? null;
                    $student->classroom_id = $this->classroom;
                    $student->school_id = $this->school;

                    // ✅ Validate trip type
                    if (isset($row['trip_type']) && !in_array($row['trip_type'], array_keys($tripTypes))) {
                        $this->errors[] = "Row {$lineNumber}: Invalid trip type.";
                        continue;
                    } else {
                        $student->trip_type = $tripTypes[$row['trip_type']] ?? "full_day";
                    }

                    // ✅ Validate bus
                    if (isset($row['bus'])) {
                        $bus = Bus::where('school_id', $this->school)->where('name', $row['bus'])->first();
                        if ($bus) {
                            $student->bus_id = $bus->id;
                        } else {
                            $this->errors[] = "Row {$lineNumber}: No bus found with name {$row['bus']}.";
                            continue;
                        }
                    }

                    // ✅ Map gender
                    if (isset($row['gender'])) {
                        $genders = [0 => 1, 1 => 2];
                        $student->gender_id = $genders[$row['gender']] ?? null;
                    }

                    $student->save();
                } catch (\Throwable $e) {
                    $this->errors[] = "Row {$lineNumber}: " . $e->getMessage();
                    continue;
                }
            }

            DB::commit();

            // ✅ Handle collected errors
            if (!empty($this->errors)) {
                $errorCounts = array_count_values($this->errors);

                $finalErrors = [];
                foreach ($errorCounts as $message => $count) {
                    $finalErrors[] = ($count > 1) ? "{$message} (repeated {$count} times)" : $message;
                }

                throw new \Illuminate\Validation\ValidationException(null, response()->json([
                    'errors' => true,
                    'messages' => 'Error',
                    'errorMessages' => $finalErrors,
                ], 422));
            }

        } catch (\Throwable $e) {
            DB::rollback();
            throw new \Illuminate\Validation\ValidationException(null, response()->json([
                'errors' => true,
                'messages' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 422));
        }
    }
}
