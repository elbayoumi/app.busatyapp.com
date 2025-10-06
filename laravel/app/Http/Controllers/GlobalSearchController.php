<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\Staff;



class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $text = $request->text;

        $results = [];

        // ✨ Schools
        $schools = \App\Models\School::whereAny(['name', 'email', 'phone'], 'like', "%$text%")
            ->select('id', 'name')
            ->limit(5)->get();
        foreach ($schools as $school) {
            $results[] = [
                'type' => 'School',
                'id' => $school->id,
                'name' => $school->name,
                'link' => route('dashboard.schools.show', $school->id),
            ];
        }

        // ✨ Students
        $students = \App\Models\Student::whereAny(['name', 'email', 'phone'], 'like', "%$text%")
            ->select('id', 'name')
            ->limit(5)->get();
        foreach ($students as $student) {
            $results[] = [
                'type' => 'Student',
                'id' => $student->id,
                'name' => $student->name,
                'link' => route('dashboard.students.show', $student->id),
            ];
        }

        // ✨ Staff, Courses, ... add more below

        return response()->json($results);
    }

    public function globalSearch(Request $request)
    {
        $text = $request->text;
        $results = [];

        // Define the models to search in with their respective searchable columns and route names
        $models = [
            [
                'model' => \App\Models\School::class,
                'columns' => ['name', 'email', 'phone'],
                'type' => 'School',
                'route' => 'dashboard.schools.show',
            ],
            [
                'model' => \App\Models\Student::class,
                'columns' => ['name', 'phone', 'national_id'],
                'type' => 'Student',
                'route' => 'dashboard.students.show',
            ],
            [
                'model' => \App\Models\Staff::class,
                'columns' => ['name', 'email', 'phone'],
                'type' => 'Staff',
                'route' => 'dashboard.staff.show',
            ],
            // Add more models here if needed
        ];

        foreach ($models as $modelData) {
            try {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query = $modelData['model']::query();

                // Apply where conditions for each column using 'orWhere'
                $query->where(function ($q) use ($modelData, $text) {
                    foreach ($modelData['columns'] as $column) {
                        $q->orWhere($column, 'like', "%$text%");
                    }
                });

                $items = $query->limit(5)->get();

                // Add results to the main array
                foreach ($items as $item) {
                    $results[] = [
                        'name' => $item->name,
                        'type' => $modelData['type'],
                        'link' => route($modelData['route'], $item->id),
                    ];
                }
            } catch (\Exception $e) {
                // Log error if model fails (e.g., missing column)
                logger()->error("Search error in " . $modelData['type'] . ": " . $e->getMessage());
            }
        }

        // Return the final search results
        return response()->json($results);
    }
}
