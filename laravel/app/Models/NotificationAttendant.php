<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAttendant extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'attendant_id' => 'integer',
        'from' => 'string',
        'title' => 'string',
        'body' => 'string',
        'route' => 'string',
        'shown' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function attendants()
    {
        return $this->belongsTo(Attendant::class, 'attendant_id');
    }
}
