<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',                    // Cast to integer
        'name' => 'string',                   // Cast to string (for varchar columns)
        'notes' => 'string',                  // Cast to string (for text columns)
        'status' => 'integer',                // Cast to integer
        'created_at' => 'datetime',           // Cast to datetime (automatically handled by Laravel)
        'updated_at' => 'datetime',           // Cast to datetime (automatically handled by Laravel)
        'order' => 'integer',                 // Cast to integer
    ];
    public function schools()
    {
        return $this->belongsToMany(School::class,'school_grade');
    }

    public function classroomg()
    {
        return $this->hasMany(Classroom::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'grade_id');
    }
}
