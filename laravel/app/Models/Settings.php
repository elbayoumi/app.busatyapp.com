<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];

    protected $appends = [
        'light_logo_full_path',
        'dark_logo_full_path',
        'favicon_full_path',
        'meta_image_full_path',
        'meta_image_full_path'
    ];


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function light_logo()
    {
        return $this->images()->orderBy('id', 'desc')->where('type', 'light_logo')->first();
    }

    public function getLightLogoFullPathAttribute()
    {
        if ($this->light_logo() != null) {
            return asset($this->light_logo()->src);
        }
        return null;
    }

    public function dark_logo()
    {
        return $this->images()->orderBy('id', 'desc')->where('type', 'dark_logo')->first();
    }

    public function getDarkLogoFullPathAttribute()
    {
        if ($this->dark_logo() != null) {
            return asset( $this->dark_logo()->src);
        }
        return null;
    }

    public function favicon()
    {
        return $this->images()->orderBy('id', 'desc')->where('type', 'favicon')->first();
    }

    public function getFaviconFullPathAttribute()
    {
        if ($this->favicon() != null) {
            return asset( $this->favicon()->src);
        }
        return null;
    }

    public function dashboard_logo()
    {
        return $this->images()->orderBy('id', 'desc')->where('type', 'dashboard_logo')->first();
    }

    public function getDashboardLogoFullPathAttribute()
    {
        if ($this->dashboard_logo() != null) {
            return asset( $this->dashboard_logo()->src);
        }
        return null;
    }

    public function meta_image()
    {
        return $this->images()->orderBy('id', 'desc')->where('type', 'meta_image')->first();
    }

    public function getMetaImageFullPathAttribute()
    {
        if ($this->meta_image() != null) {
            return asset( $this->meta_image()->src);
        }
        return null;
    }

}
