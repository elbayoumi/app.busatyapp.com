<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Static_messages extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer', // Cast big integer to integer
        'message' => 'string', // Cast longtext to string
        'created_at' => 'datetime', // Cast timestamp to datetime
        'updated_at' => 'datetime', // Cast timestamp to datetime
        'trip_type' => 'string', // Cast enum to string
        'school_id' => 'integer', // Cast big integer to integer
    ];
}
