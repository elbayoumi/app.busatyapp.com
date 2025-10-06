<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdesSchool extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'ades_id' => 'integer',
        'to' => 'string',
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function ades()
    {
        return $this->belongsTo(Ades::class, 'ades_id');
    }
}
