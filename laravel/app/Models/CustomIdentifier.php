<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomIdentifier extends Model
{
    use Traits\CommonTrait;
    protected $fillable = ['identifier', 'identifiable_type', 'identifiable_id'];
    /**
     * Morph relationship back to the model it belongs to.
     */
    public function identifiable()
    {
        return $this->morphTo();
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->identifier)) {
                $model->identifier = 'custom_' . Str::uuid(); // Generate a default value
            }
        });
    }
}
