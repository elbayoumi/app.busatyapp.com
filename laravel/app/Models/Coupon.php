<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',          // Cast to integer
        'staff_id' => 'integer',    // Cast to integer
        'code' => 'string',         // Cast to string
        'model' => 'string',         // Cast to string
        'discount' => 'decimal:2',  // Cast to decimal with 2 decimal places
        'user_limit' => 'integer', // Cast to integer
        'usage_limit' => 'integer', // Cast to integer
        'allow_at' => 'datetime',   // Cast to datetime
        'expires_at' => 'datetime', // Cast to datetime
        'created_at' => 'datetime', // Cast to datetime
        'updated_at' => 'datetime', // Cast to datetime
    ];
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function uses()
    {
        return $this->hasMany(CouponUse::class);
    }
}
