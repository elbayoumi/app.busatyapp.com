<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentSchoolMessage extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $table = 'my__parent_school_message';
    protected $casts = [
        'id' => 'integer',                // Cast to integer
        'my__parent_id' => 'integer',     // Cast to integer
        'status' => 'integer',            // Cast to integer
        'school_messages_id' => 'integer',// Cast to integer
        'created_at' => 'datetime',       // Cast to datetime
        'updated_at' => 'datetime',       // Cast to datetime
    ];
    public function school_messages()
    {
        return $this->belongsTo(School_messages::class,'school_messages_id');
    }
    public function parents()
    {
        return $this->belongsTo(My_Parent::class,'my__parent_id');
    }

}
