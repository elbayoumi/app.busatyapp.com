<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',          // Cast to integer
        'name' => 'string',         // Cast to string (for varchar columns)
        'created_at' => 'datetime', // Cast to datetime (automatically handled by Laravel)
        'updated_at' => 'datetime', // Cast to datetime (automatically handled by Laravel)
    ];
}
