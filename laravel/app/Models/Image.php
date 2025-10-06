<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use Traits\CommonTrait;

    protected $fillable = ['src', 'type'];
    protected $casts = [
        'id' => 'integer',           // Cast to integer
        'imageable_type' => 'string', // Cast to string (for varchar columns)
        'imageable_id' => 'integer',  // Cast to integer
        'src' => 'string',            // Cast to string
        'type' => 'string',           // Cast to string
        'created_at' => 'datetime',   // Cast to datetime (automatically handled by Laravel)
        'updated_at' => 'datetime',   // Cast to datetime (automatically handled by Laravel)
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
