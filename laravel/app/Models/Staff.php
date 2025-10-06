<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Staff extends Authenticatable
{
    use HasApiTokens, Traits\CommonTrait, Notifiable, HasRoles;
    protected $guard = 'web';

    protected $guard_name = 'web';

    protected $appends = ['logo_path'];

    public const STATUS_DEACTIVATED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_BLOCKED = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */


    public function getLogoPathAttribute()
    {

        if ($this->logo != null) {
            return asset('uploads/staffs_logo/' . $this->logo);
        }
        return null;
    } //end of image path attribute
    protected $casts = [
        'id' => 'integer',

        'email_verified_at' => 'datetime',
        'gender' => 'boolean',
        'birth_date' => 'date',
        'dark_mode' => 'boolean',
        'status' => 'integer',
    ];
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
