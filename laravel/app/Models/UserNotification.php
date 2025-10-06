<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use Traits\CommonTrait;
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
        'read_at' => 'boolean',

    ];
    protected $guarded = [];
    public function userable()
    {
        return $this->morphTo();
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
    // public function notifications()
    // {
    //     return $this->belongsToMany(Notification::class, 'user_notifications', 'userable_id', 'notification_id');
    // }
}
