<?php

namespace App\Models;

use App\Enums\TripDayStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripDay extends Model
{
    // 🔹 Table name (Laravel by default uses trip_days, so not mandatory unless you want explicit)
    protected $table = 'trip_days';

    // 🔹 Fillable attributes (safe mass assignment)
    protected $fillable = [
        'trip_id',
        'date',
        'status',
        'bus_id',
        'openable_id',
        'openable_type',
        'opened_at',
        'closed_at',
    ];

    // 🔹 Dates casting
    protected $casts = [
        'status'    => TripDayStatus::class, // enum cast
        'date' => 'date',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
    protected $appends = ['status_name'];

    // -------------------------
    // 🔹 Relationships
    // -------------------------

    // Trip relation (parent trip template)
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    // Bus relation (snapshot bus for that day)
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    // Morph relation (who opened this trip day)
    public function openable(): MorphTo
    {
        return $this->morphTo();
    }

    // Attendances relation (students check-ins for this trip day)
    public function attendances(): HasMany
    {
        return $this->hasMany(TripDayAttendance::class);
    }
    public function attendants()
    {
        // All join records (active + historical)
        return $this->hasMany(TripDayAttendant::class);
    }

    public function activeAttendants()
    {
        // Only current active attendants (joined but not left)
        return $this->hasMany(TripDayAttendant::class)->where('active', 1);
    }
    public function getStatusNameAttribute(): string
    {
        return $this->status->name; // SCHEDULED | OPEN | CLOSED
    }
}
