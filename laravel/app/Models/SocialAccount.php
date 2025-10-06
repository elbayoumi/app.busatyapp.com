<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'provider', 'provider_id', 'email', 'avatar', 'token'
    ];

    public function userable()
    {
        return $this->morphTo();
    }
}
