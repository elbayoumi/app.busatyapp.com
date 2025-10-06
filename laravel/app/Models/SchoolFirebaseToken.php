<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolFirebaseToken extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer', // Cast big integer to integer
        'school_id' => 'integer', // Cast big integer to integer
        'firebase_token' => 'string', // Cast varchar to string
        'created_at' => 'datetime', // Cast timestamp to datetime
        'updated_at' => 'datetime', // Cast timestamp to datetime
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
