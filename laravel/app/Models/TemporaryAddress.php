<?php

namespace App\Models;

use App\Enum\TemporaryAddressStatusEnum;
use App\Observers\TemporaryAddressObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemporaryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'address',
        'latitude',
        'longitude',
        'from_date',
        'to_date',
        'accept_status',
    ];
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        // 'accept_status' => TemporaryAddressStatusEnum::class,
    ];
    protected $appends = ['is_current'];

    # accessors
    public function getAcceptStatusAttribute($value)
    {
        return TemporaryAddressStatusEnum::getArray()[$value] ?? $value;
    }


    # relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function getIsCurrentAttribute(): bool
    {
        $today = Carbon::today();
        $acceptStatus = $this->getRawOriginal('accept_status');

        return $acceptStatus == 1 &&
               $this->from_date <= $today &&
               $this->to_date >= $today;
    }

    public function oldBus()
    {
        return $this->belongsToMany(Bus::class, 'temporary_address_old_buses' , 'temporary_address_id', 'bus_id');
    }

    # scopes
    public function scopeCurrentActive($query)
    {
        $today = Carbon::now()->format('Y-m-d');
        return $query
              ->where('accept_status', 1)
              ->where('to_date', '>=', $today)
              ->where('from_date', '<=', $today);
    }

    public function scopeFilters($query, $filters)
    {
        return $query
            ->when($filters['student_id'] ?? false, function ($query, $student_id) {
                $query->where('student_id', $student_id);
            })
            ->when( $filters['parent_id'] ?? false, function ($query, $parentId) {
                $query->whereHas('student.my_Parents', function ($q) use ($parentId) {
                  $q->where('my__parents.id', $parentId);
                });
            })
            ->when( $filters['school_id'] ?? false, function ($query, $schoolId) {
                $query->whereHas('student', function ($q) use ($schoolId) {
                  $q->where('school_id', $schoolId);
                });
            })
            ->when(isset($filters['accept_status']), function ($query) use ($filters) {
                $query->where('accept_status', $filters['accept_status']);
            })
            ->when($filters['from_date'] ?? false, function ($query, $from_date) {
                $query->where('from_date', '>=', $from_date);
            })
            ->when($filters['to_date'] ?? false, function ($query, $to_date) {
                $query->where('to_date', '<=', $to_date);
            });
    }

    # observers
    protected static function boot()
    {
        parent::boot();

        static::observe(TemporaryAddressObserver::class);
    }
}
