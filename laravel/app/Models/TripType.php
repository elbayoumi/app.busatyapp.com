<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    use Traits\CommonTrait;
    protected $fillable = ['name', 'description'];

    public function owner()
    {
        return $this->morphTo('tripable');
    }


    public function subscribedUsers()
    {
        return $this->morphToMany(User::class, 'userable', 'trip_type_users', 'userable_id', 'trip_type_id');
    }
}
