<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    use Traits\CommonTrait;

    protected $fillable = ['fcm_token'];

    /**
     * Get the parent model (user, student, etc.) of the FCM token.
     */
    public function tokenable()
    {
        return $this->morphTo();
    }
}
