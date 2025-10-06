<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedTrip extends Model
{
    use Traits\CommonTrait;
    protected $guarded=[];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',              // Cast to integer
        'school_id' => 'integer',       // Cast to integer
        'type' => 'string',             // Cast to string (since 'type' is an enum)
        'time_start' => 'datetime:H:i:s', // Cast to datetime with time format
        'time_end' => 'datetime:H:i:s',   // Cast to datetime with time format
    ];
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }

}
