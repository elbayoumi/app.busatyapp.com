<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatedBy extends Model
{

    use Traits\CommonTrait;
    protected $table = 'created_by';
    protected $fillable = ['userable_id', 'userable_type', 'creatable_id', 'creatable_type'];

    public function userable()
    {
        return $this->morphTo();
    }

    public function creatable()
    {
        return $this->morphTo();
    }
}
