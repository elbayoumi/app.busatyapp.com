<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripRoute extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'trip_id',
        'bus_id',
        'latitude',
        'longitude',
        'type',
        'created_at',
    ];
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    /**
     * Scope a query to filter routes by search query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('trip_id', 'like', '%' . $search . '%')
                    ->orWhere('bus_id', 'like', '%' . $search . '%');
            });
        });
    }
}
