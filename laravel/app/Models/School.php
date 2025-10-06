<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class School extends Authenticatable  implements MustVerifyEmail
{


    use Traits\CommonGourd;

    protected $guarded = [];
    protected $appends = ['logo_path','subscription_status','identity_preview'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'id' => 'integer', // Cast big integer to integer
        'name' => 'string', // Cast varchar to string
        'email' => 'string', // Cast varchar to string
        'phone' => 'string', // Cast varchar to string
        'email_verified_at' => 'string', // Cast email_verified_at to string
        'password' => 'string', // Cast varchar to string
        'address' => 'string', // Cast varchar to string
        'city_name' => 'string', // Cast varchar to string
        'status' => 'integer', // Cast int to integer
        'logo' => 'string', // Cast varchar to string
        'remember_token' => 'string', // Cast varchar to string
        'created_at' => 'datetime', // Cast timestamp to datetime
        'updated_at' => 'datetime', // Cast timestamp to datetime
        'typeAuth' => 'string', // Cast varchar to string
        'latitude' => 'string', // Cast varchar to string (or float if using decimal values)
        'longitude' => 'string', // Cast varchar to string (or float if using decimal values)
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function getLogoPathAttribute()
    {

        if ($this->logo != null) {
            return asset('uploads/schools_logo/' . $this->logo);
        }
        return null;
    } //end of image path attribute

    public function getAddressAttribute($attr)
    {


        return $attr ?? __('Address not found.');
    } //end of image path attribute

    public function fcmTokens()
    {
        return $this->morphMany(FcmToken::class, 'tokenable');
    }


    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'school_grade');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function userNotifications()
    {
        return $this->morphMany(UserNotification::class, 'userable');
    }
    public function fixedTrip()
    {
        return $this->hasMany(FixedTrip::class);
    }
    /**
     * Summary of buses
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buses()
    {
        return $this->hasMany(Bus::class, 'school_id');
    }
    public function busById($id)
    {
        return $this->buses()->where('id', $id);
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }
    function grade_parents_byBusId($id)
    {
        return $this->busById($id)->with('students', function ($q) {
            $q->with(['grade', 'my_Parents']);
        });
    }
    // public function parents()
    // {
    //     return $this->hasMany(My_Parent::class, 'school_id');
    // }

    public function attendants()
    {
        return $this->hasMany(Attendant::class, 'school_id');
    }

    public function trip()
    {
        return $this->hasMany(Trip::class, 'bus_id');
    }

    public function adesSchool()
    {
        return $this->hasMany(AdesSchool::class, 'school_id');
    }
    public function fbToken()
    {
        return $this->hasMany(SchoolFirebaseToken::class);
    }
    public function getSubscriptionStatusAttribute()
    {
        $currentDate = now();
        return $this->subscriptions()
        ->whereDate('end_date', '>', $currentDate)
        ->exists();

    }
    public function getIdentityPreviewAttribute(): bool
    {
        return $this->name && $this->email && $this->phone && $this->address && $this->latitude && $this->longitude;
    }
}
