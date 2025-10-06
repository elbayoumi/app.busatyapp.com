<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentFirebaseToken extends Model
{
    use Traits\CommonTrait;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'parent_firebase_tokens';
    public function parents()
    {
        return $this->belongsTo(My_Parent::class,'my__parent_id');
    }


}
