<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ades extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'body' => 'string',
        'link' => 'string',
        'image' => 'string',
        'alt' => 'string',
    ];
    public function adesSchool()
    {
        return $this->hasMany(AdesSchool::class, 'ades_id');
    }
    public function getImagePathAttribute()
    {

        if ($this->image != null) {
            return asset('uploads/ades_image/' . $this->image);
        }
        return null;
    } //end of image path attribute
    protected $appends = ['image_path'];
}
