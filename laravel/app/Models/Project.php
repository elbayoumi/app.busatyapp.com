<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Make sure to add this line

class Project extends Model
{
    use Traits\CommonTrait;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'username',
        'password', // Ensure to manage the password securely
    ];

    protected $casts = [
        'client_id' => 'string', // Cast client_id to string
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // Check if client_id is empty, then generate a new UUID
            if (empty($project->client_id)) {

                // $project->client_id = (string) Str::uuid();
                $project->client_id = (string) uniqid('client_');
            }
        });
        // static::updating(function ($project) {
        //     // Check if client_id is empty, then generate a new UUID
        //     if (empty(request()->password)) {

        //         // $project->client_id = (string) Str::uuid();
        //         $project->password = $project->password;
        //     }
        // });
    }
}
