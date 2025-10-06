<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class My_Parent extends Authenticatable  implements MustVerifyEmail

{
    use  Traits\CommonGourd;

    protected $table = 'my__parents';
    protected $guarded = [];
    protected $appends = ['logo_path', 'subscription_status', 'identity_preview'];


    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'id' => 'integer',                // Cast to integer
        'name' => 'string',              // Cast to string
        'email' => 'string',             // Cast to string
        'phone' => 'string',             // Cast to string
        'email_verified_at' => 'string', // Cast to string (if you intend to store non-date data)
        'remember_token' => 'string',    // Cast to string
        'password' => 'string',          // Cast to string
        'address' => 'string',           // Cast to string
        'status' => 'integer',           // Cast to integer
        'logo' => 'string',              // Cast to string
        'typeAuth' => 'string',          // Cast to string
        'created_at' => 'datetime',      // Cast to datetime
        'updated_at' => 'datetime',      // Cast to datetime
        'firebase_token' => 'string',    // Cast to string
    ];
    public function fcmTokens()
    {
        return $this->morphMany(FcmToken::class, 'tokenable');
    }
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    public function getLogoPathAttribute()
    {

        if ($this->logo != null) {
            return asset('uploads/parents_logo/' . $this->logo);
        }
        return null;
    } //end of image path attribute
    public function addchild($student_id)
    {
        // تحقق من عدم وجود علاقة مكررة
        My__parent_student::createOrUpdate($this->id, $student_id);
    }
    // public function schools()
    // {
    //     return $this->belongsTo(School::class, 'school_id');
    // }

    // public function notifications()
    // {
    //     return $this->morphedByMany(self::class, 'userable', 'user_notifications', 'userable_id', 'notification_id');
    // }

    /**
     * Get the Firebase token to be used for sending notifications.
     *
     * @return string
     */
    public function routeNotificationForFirebase()
    {
        return $this->firebase_token;
    }
    // public function students()
    // {
    //     return $this->belongsToMany(Student::class,My__parent_student::class);
    // }
    public function students()
    {
        return $this->belongsToMany(Student::class, My__parent_student::class, 'my__parent_id', 'student_id');
    }

    // public function AttendantParentMessage()
    // {
    //     return $this->belongsToMany(AttendantParentMessage::class,'my__parent_student');
    // }
    // In the My_Parent model
    public function hasStudent($studentId)
    {
        return $this->students()->wherePivot('student_id', $studentId)->exists();
    }
    public function notification()
    {
        return $this->hasMany(NotificationParant::class);
    }

    public function school_messages()
    {
        return $this->belongsToMany(School_messages::class, 'my__parent_school_message');
    }


    public function couponUses()
    {
        return $this->morphMany(Subscription::class, 'subscriptable');
    }

    public function address()
    {
        return $this->hasMany(Address::class, 'my__parent_id');
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
        return $this->name && $this->email && $this->phone && $this->address;
    }
}
