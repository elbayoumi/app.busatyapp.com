<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class App extends Model
{
    use Traits\CommonTrait;
    protected $keyType = 'string';
    public $incrementing = false; // لتعطيل الـ auto-increment
    protected $table = 'apps'; // Define the table name explicitly

    protected $fillable = ['id','name', 'status','version','is_updating','google_auth']; // Mass assignable attributes

    protected $casts = [
        'status' => 'integer',
        'is_updating' => 'boolean',
        'version' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // Manually generate a UUID
            }
        });
}

}
