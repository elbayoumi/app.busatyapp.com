<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Topic extends Model
{
    use Traits\CommonTrait,Notifiable;

    protected $fillable = ['id','name'];

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'topic_notifications');
    }

       /**
     * Polymorphic relationship to users, staff, or students.
     *
     * @param string $userModel The user model class (e.g., User::class, Staff::class, Student::class)
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users($userModel)
    {
        // Using morphedByMany to store the correct userable_type based on the model sent
        return $this->morphedByMany($userModel, 'userable', 'topic_users', 'topic_id', 'userable_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(Notification::class, 'topic_users');
    // }
}
