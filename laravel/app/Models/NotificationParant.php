<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationParant extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'parant_id' => 'integer',
        'from' => 'string',
        'title' => 'string',
        'body' => 'string',
        'route' => 'string',
        'shown' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function my_Parents()
    {
        return $this->belongsTo(My_Parent::class, 'parent_id');
    }
    // public function My__parent_student()
    // {
    //     return $this->belongsTo(My__parent_student::class, 'parent_id');
    // }
}
