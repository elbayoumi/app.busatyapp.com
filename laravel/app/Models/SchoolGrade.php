<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    use Traits\CommonTrait;
    protected $table='school_grade';
    protected $guarded=[];

    protected $casts = [
        'id' => 'integer', // Cast big integer to integer
        'school_id' => 'integer', // Cast big integer to integer
        'grade_id' => 'integer', // Cast big integer to integer
        'created_at' => 'datetime', // Cast timestamp to datetime
        'updated_at' => 'datetime', // Cast timestamp to datetime
    ];
}
