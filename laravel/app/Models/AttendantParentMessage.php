<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendantParentMessage extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer', // Cast to integer
        'attendant_id' => 'integer', // Cast to integer
        'student_id' => 'string', // Cast to integer
        'created_at' => 'datetime', // Cast to datetime
        'updated_at' => 'datetime', // Cast to datetime
        'status' => 'integer', // Cast to integer
        'message' => 'string', // Cast to string
        'message_en' => 'string', // Cast to string
        'title' => 'string', // Cast to string
        'trip_type' => 'string', // Cast to string (enum values are treated as strings)
        'trip_id' => 'integer', // Cast to integer
        'bus_id' => 'integer', // Cast to integer
        'notifications_type' => 'string', // Cast to string
    ];
    public function attendant()
    {
        return $this->belongsTo(Attendant::class,'attendant_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class,'bus_id');
    }
}
