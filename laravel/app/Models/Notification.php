<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Traits\CommonTrait;

    protected $fillable = ['data'];

    protected $casts = [
        'id' => 'integer',
        'data' => 'array', // Automatically casts 'data' to an array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode([
            'from' => $value['from'] ?? null,
            'title' => $value['title'] ?? null,
            'body' => $value['body'] ?? null,
            'type' => $value['type'] ?? null,
            'additional' => $value['additional'] ?? null,
        ]);
    }
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }
    public function userNotifications()
    {
        return $this->morphedByMany(UserNotification::class, 'userable', 'user_notifications', 'notification_id', 'userable_id');
    }


    // علاقة بـ My_Parent
    public function myParents()
    {
        return $this->morphedByMany(My_Parent::class, 'userable', 'user_notifications', 'notification_id', 'userable_id');
    }

    // علاقة بـ Attendant
    public function attendants()
    {
        return $this->morphedByMany(Attendant::class, 'userable', 'user_notifications', 'notification_id', 'userable_id');
    }
    public function users($userModel)
    {
        return $this->morphedByMany($userModel, 'userable', 'user_notifications', 'notification_id', 'userable_id');
    }

    // علاقة بـ School
    public function schools()
    {
        return $this->morphedByMany(School::class, 'userable', 'user_notifications', 'notification_id', 'userable_id');
    }
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topic_notifications');
    }
}
