<?php

namespace App\Models;

use App\Casts\AddressStatusEnumCast;
use App\Enum\AddressStatusEnum;
use App\Observers\AddressObserver;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use Traits\CommonTrait;
    protected $appends = ['status_text'];

    protected $guarded = [];

    public const STATUS_NEW = 0;
    public const STATUS_ACCEPT = 1;
    public const STATUS_UNACCEPT = 2;
    public const STATUS_PROCESSING = 3;

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'bus_id' => 'integer',
        'status' => AddressStatusEnumCast::class,  // Apply the custom cast
        'latitude' => 'string',
        'longitude' => 'string',
        'my__parent_id' => 'integer',
        'student_id' => 'string',
        'old_address' => 'string',
        'old_latitude' => 'string',
        'old_longitude' => 'string',
        'New_address' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function parent()
    {
        return $this->belongsTo(My_Parent::class, 'my__parent_id');
    }
    public function scopeWork($query){
        return $query->where('status', AddressStatusEnum::ACCEPT);
    }
    public function scopeNotWork($query){
        return $query->where('status', AddressStatusEnum::NEW);
    }
    public function scopeWatting($query){
        return $query->where('status', AddressStatusEnum::PROCESSING);
    }

    public function getStatusTextAttribute()
    {
        $status = $this->status;

        return [
            'text' => $status->getText(),
            'color' => $status->getColor(),
        ];
    }

    protected static function boot()
{
    parent::boot();

    static::observe(AddressObserver::class);
}

}
