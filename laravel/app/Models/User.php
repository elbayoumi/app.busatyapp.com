<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Traits\CommonTrait , Notifiable;

    protected $appends = ['avatar_full_path'];

    public const MALE_GENDER = 1;
    public const FEMALE_GENDER = 0;


    public const STATUS_DEACTIVATED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_BLOCKED = 6;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    // public function firebase_tokens()
    // {
    //     return $this->hasMany(FirebaseTokes::class);
    // }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function avatar()
    {
        return $this->images()->where('type', 'avatar')->first();
    }

    public function getAvatarFullPathAttribute()
    {
        if ($this->avatar() != null) {
            return asset('storage' .'/' . $this->avatar()->src);
        }
        return null;
    }

    // public function plans()
    // {
    //     return $this->hasMany(Plan::class);
    // }

}
