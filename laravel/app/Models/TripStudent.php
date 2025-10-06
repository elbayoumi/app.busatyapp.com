<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripStudent extends Model
{
    protected $table = 'trip_students';

    // Mass assignable fields
    protected $fillable = [
        'trip_id',
        'student_id',
    ];

    /**
     * Relationship: TripStudent belongs to a Trip
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    /**
     * Relationship: TripStudent belongs to a Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
