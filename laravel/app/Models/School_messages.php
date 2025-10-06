<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School_messages extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'event_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function parents()
    {
        return $this->belongsToMany(My_Parent::class,'my__parent_school_message');
    }

}
