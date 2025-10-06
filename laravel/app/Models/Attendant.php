<?php

namespace App\Models;

use App\Observers\AttendantObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Attendant extends Authenticatable  implements MustVerifyEmail
{
    use  Traits\CommonGourd;



    protected $guarded = [];

    protected $appends = ['logo_path'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer', // Cast to integer
        'email' => 'string', // Cast to string
        'username' => 'string', // Cast to string
        'password' => 'string', // Cast to string
        'name' => 'string', // Cast to string
        'gender_id' => 'integer', // Cast to integer
        'school_id' => 'integer', // Cast to integer
        'religion_id' => 'integer', // Cast to integer
        'type__blood_id' => 'integer', // Cast to integer
        'bus_id' => 'integer', // Cast to integer
        'Joining_Date' => 'date', // Cast to date
        'address' => 'string', // Cast to string
        'city_name' => 'string', // Cast to string
        'status' => 'integer', // Cast to integer
        'logo' => 'string', // Cast to string
        'type' => 'string', // Cast to string
        'phone' => 'string', // Cast to string
        'birth_date' => 'date', // Cast to date
        'email_verified_at' => 'string', // Cast to string (consider changing this if it should be a date)
        'remember_token' => 'string', // Cast to string
        'created_at' => 'datetime', // Cast to datetime
        'updated_at' => 'datetime', // Cast to datetime
        'typeAuth' => 'string', // Cast to string
        'firebase_token' => 'string', // Cast to string
    ];


    public function getLogoPathAttribute()
    {

        if ($this->logo != null) {
            return asset('uploads/attendants_logo/' . $this->logo);
        }
        return null;
    } //end of image path attribute
    public function fcmTokens()
    {
        return $this->morphMany(FcmToken::class, 'tokenable');
    }
    // public function notifications()
    // {
    //     return $this->morphMany(Notification::class, 'notificationable');
    // }
    // public function notifications()
    // {
    //     return $this->morphedByMany(Notification::class, 'userable', 'user_notifications', 'userable_id', 'notification_id');
    // }
    public function userNotifications()
    {
        return $this->morphMany(UserNotification::class, 'userable');
    }
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get all trips this attendant is assigned to.
     */
    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_attendants', 'attendant_id', 'trip_id')
            ->using(TripAttendant::class);
    }
    public function attendantParentMessage()
    {
        return $this->hasMany(AttendantParentMessage::class, 'attendant_id');
    }

    public function notification()
    {
        return $this->hasMany(NotificationAttendant::class);
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }
    public function typeBlood()
    {
        return $this->belongsTo(Type_Blood::class, 'type__blood_id');
    }

    public function createdRecords()
    {
        return $this->morphOne(CreatedBy::class, 'userable');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    //     public function bus_admins()
    //     {
    //         return $this->hasOne(Bus::class, 'attendant_admins_id');
    //     }


    //     public function bus_driver()
    //     {
    //         return $this->hasOne(Bus::class, 'attendant_driver_id');
    //     }



    //     public function attendant_admins()
    //     {
    //         return $this->hasMany(Student::class,'attendant_admins_id');
    //     }

    //     public function attendant_driver()
    //     {
    //         return $this->hasMany(Student::class,'attendant_driver_id');
    //     }


    //     public function trip_admins()
    //     {
    //         return $this->hasMany(Trip::class, 'attendant_admins_id');
    //     }
    //     public function trip_driver()
    //     {
    //         return $this->hasMany(Trip::class, 'attendant_driver_id');
    //     }
    public function tripDayLinks()
    {
        return $this->hasMany(TripDayAttendant::class, 'attendant_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::observe(AttendantObserver::class);
    }
}
