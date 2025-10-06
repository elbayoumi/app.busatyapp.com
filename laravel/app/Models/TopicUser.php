<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicUser extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['topic_id', 'userable_id', 'userable_type', 'joined_at', 'status'];

}
