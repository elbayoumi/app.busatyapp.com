<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School_verfication extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'code' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(School::class,'user_id');
    }
}
