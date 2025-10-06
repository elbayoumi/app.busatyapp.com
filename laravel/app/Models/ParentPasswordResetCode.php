<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentPasswordResetCode extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'code' => 'string',
        'try' => 'string',
        'created_at' => 'datetime',
    ];
}
