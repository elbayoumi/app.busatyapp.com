<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];

    public const STATUS_ABSENT = 0;
    public const STATUS_PRESENCE = 1;

    public const STATUS_AT_SCHOOL = 2;

    public const STATUS_AT_HOME = 3;


    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'student_id' => 'string',
        'attendence_date' => 'date',
        'trip_id' => 'integer',
        'attendence_status' => 'integer',
        'attendance_type' => 'string', // Use 'string' for enum types in the application logic
    ];

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class,'trip_id');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class,'bus_id');
    }

    public function scopeParents($query){
        return $query->with(['students.my_Parents']);
    }
    public function scopePresent($query){
        return $query->where('attendence_status', 0);
    }

    // public function scopeOnBus($query,$trip_id){
    //     return $query->select('attendance_type', 'attendence_date', 'trip_id', 'student_id')
    //     ->where('trip_id', $trip_id)
    //     ->present()
    //     ->with([
    //       'students.temporaryAddresses' => function ($query) {
    //           $query->currentActive();
    //         },
    //       ])
    //     ->parents()->get();
    // }
    public function scopeOnBus($query, $trip_id)
    {
        return $query->select('attendance_type', 'attendence_date', 'trip_id', 'student_id')
            ->where('trip_id', $trip_id)
            ->present()
            ->with([
                'students.temporaryAddresses' => function ($query) {
                    $query->currentActive();
                },
            ])
            ->parents()
            ->get()
            ->map(function ($attendance) {
                $student = $attendance->students;

                // Add boolean has_current_address
                $student->has_current_address = $student->temporaryAddresses->contains(fn($addr) => $addr->is_current);

                return $attendance;
            });
    }



    public function tr_attendence_status()
    {

        $data = [];
        if ($this->attendence_status == self::STATUS_ABSENT) {
            $data =[
                'text' => 'غائب',
                'color' => 'danger',
            ];
            return $data;
        }

        if ($this->attendence_status == self::STATUS_PRESENCE) {
            $data =[
                'text' => 'في الباص',
                'color' => 'info',
            ];
            return $data;
        }

        if ($this->attendence_status == self::STATUS_AT_SCHOOL) {
            $data =[
                'text' => 'في المدرسة',
                'color' => 'success',
            ];
            return $data;
        }

        if ($this->attendence_status == self::STATUS_AT_HOME) {
            $data =[
                'text' => 'في المنزل',
                'color' => 'success',
            ];
            return $data;

        }
        return 'Undefined';
    }



}
