<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];
    protected $appends = ['driver', 'admin'];

    protected $casts = [
        'id' => 'integer', // Cast to integer
        'name' => 'string', // Cast to string
        'notes' => 'string', // Cast to string (text fields are typically cast to string)
        'status' => 'integer', // Cast to integer
        'school_id' => 'integer', // Cast to integer
        'car_number' => 'string', // Cast to string
        'created_at' => 'datetime', // Cast to datetime
        'updated_at' => 'datetime', // Cast to datetime
    ];

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function attendants()
    {
        return $this->hasMany(Attendant::class, 'bus_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'bus_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'bus_id');
    }

    public function addresses_old()
    {
        return $this->hasMany(Address::class, 'old_bus_id');
    }


    public function attendantParentMessage()
    {
        return $this->hasMany(AttendantParentMessage::class,'bus_id');
    }
    public function trip()
    {
        return $this->hasMany(Trip::class,'bus_id');
    }
    public function routes()
    {
        return $this->hasMany(TripRoute::class, 'bus_id');
    }
    public function getDriverAttribute()
    {
        $driver = '';
        if($this->attendants()->count() >  0 && $this->attendants()->where('type', 'drivers')->count() == 1) {
            $driver = $this->attendants()->where('type', 'drivers')->first();
            return $driver;
        }

        return null;

    }

    public function getAdminAttribute()
    {
        $admin = '';
        if($this->attendants()->count() >  0 && $this->attendants()->where('type', 'admins')->count() == 1) {
            $admin = $this->attendants()->where('type', 'admins')->first();
            return $admin;
        }

        return null;

    }

}
