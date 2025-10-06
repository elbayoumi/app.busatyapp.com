<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $students;
    protected $lng;
    public function __construct($students, $lng = 'ar')
    {
        $this->students = $students;
        $this->lng = $lng;
    }

    public function view(): View
    {
        if ($this->lng == 'en') {
            return view('dashboard.exports.students_en', [
                'students' =>   $this->students,
            ]);
        } else {
            return view('dashboard.exports.students', [
                'students' =>   $this->students,
            ]);
        }
    }
}
