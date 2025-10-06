<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUse extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',           // Cast to integer
        'coupon_id' => 'integer',    // Cast to integer
        'status' => 'string',        // Cast to string (since 'status' is an enum)
        'created_at' => 'datetime',  // Cast to datetime
        'updated_at' => 'datetime',  // Cast to datetime
    ];
    public function usedBy()
    {
        return $this->morphTo(); // polymorphic relation with the model that used the coupon
    }

    public function coupons()
    {
        return $this->belongsTo(Coupon::class,'coupon_id');
    }

}
