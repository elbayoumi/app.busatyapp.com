<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryAddressOldBus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    # relations
    public function bus () 
    {
        return $this->belongsTo(Bus::class);
    }  

    public function temporaryAddress()
    {
        return $this->belongsTo(TemporaryAddress::class);
    }
}
