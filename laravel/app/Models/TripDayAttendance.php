<?php

namespace App\Models;

use App\Enum\TripStudentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TripDayAttendance extends Model
{
    protected $fillable = [
        'trip_day_id',
        'student_id',
        'status',
        'check_in_at',
        'check_out_at',
        'check_in_lat',
        'check_in_long',
        'check_out_lat',
        'check_out_long',
        'source',
        'notes',
    ];

    protected $casts = [
        'status' => TripStudentStatusEnum::class, // cast to Enum
        'check_in_at'  => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function tripDay()
    {
        return $this->belongsTo(TripDay::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function creator(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    public function isExcused(): bool
    {
        return $this->status === 'excused';
    }

    public function isCheckedIn(): bool
    {
        return !is_null($this->check_in_at);
    }

    public function isCheckedOut(): bool
    {
        return !is_null($this->check_out_at);
    }
}
