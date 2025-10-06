<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSchool extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'from' => 'string',
        'title' => 'string',
        'body' => 'string',
        'route' => 'string',
        'shown' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
