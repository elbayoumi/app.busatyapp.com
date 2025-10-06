<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailSubscription extends Model
{
    use Traits\CommonTrait;

    protected $table = 'email_subscriptions';

    protected $fillable = [
        'userable_id',
        'userable_type',
        'email',
        'subscribed',
        'token',
        'reason',
        'unsubscribed_at',
        'ip',
        'user_agent',
        'preferences',
    ];

    protected $casts = [
        'subscribed'       => 'boolean',
        'unsubscribed_at'  => 'datetime',
        'preferences'      => 'array',
    ];

    /**
     * Polymorphic relation (User, Admin, ...)
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Ensure token exists for links (unsubscribe, etc).
     */
    public function ensureToken(): string
    {
        if (!$this->token) {
            $this->token = Str::random(40); // 40 chars (safe enough)
            $this->save();
        }
        return $this->token;
    }
}
