<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'bus_id' => 'integer',
        'my__parent_id' => 'integer',
        'student_id' => 'string',
        'attendence_date' => 'date',
        'attendence_type' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function parent()
    {
        return $this->belongsTo(My_Parent::class, 'my__parent_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class,'grade_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class,'classroom_id');
    }

    public function tr_trip_type()
    {
        if ($this->attendence_type == 'full_day') {
            return 'اليوم كامل';
        }

        if ($this->attendence_type == 'end_day') {
            return 'نهاية اليوم';
        }

        if ($this->attendence_type == 'start_day') {
            return 'بداية اليوم';
        }

        return 'Undefined';
    }

}
