<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',         // Cast to integer
        'name' => 'string',        // Cast to string
        'status' => 'integer',     // Cast to integer
        'school_id' => 'integer',  // Cast to integer
        'grade_id' => 'integer',   // Cast to integer
        'created_at' => 'datetime',// Cast to datetime
        'updated_at' => 'datetime',// Cast to datetime
    ];
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class,'grade_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

}
