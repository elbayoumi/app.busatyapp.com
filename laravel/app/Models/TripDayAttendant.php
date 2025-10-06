<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripDayAttendant extends Model
{
    protected $fillable = [
        'trip_day_id', 'attendant_id', 'joined_at', 'left_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at'   => 'datetime',
        'active'    => 'boolean', // generated column (0/1) -> boolean
    ];

    public function tripDay()
    {
        return $this->belongsTo(TripDay::class);
    }

    public function attendant()
    {
        return $this->belongsTo(Attendant::class, 'attendant_id');
    }

    public function scopeActive($q)
    {
        return $q->whereNull('left_at');
    }

    public function isActive(): bool
    {
        return is_null($this->left_at);
    }
}
