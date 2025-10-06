<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'plan_name' => 'string',
        'amount' => 'decimal:2',
        'currency' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'string',
        'payment_method' => 'string',
        'transaction_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function subscriptable()
    {
        return $this->morphTo();
    }
}
