<?php

namespace App\Models;

use App\Casts\{
    TripStatusEnumCast,
    TripTypeEnumCast,
};
use App\Enum\{
    TripStatusEnum,
    TripStudentStatusEnum,
    TripTypebBusEnum,
};

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Trip extends Model
{
    use Traits\CommonTrait, Notifiable;

    /**
     * Allow mass assignment for all fields (you already used guarded = [])
     * Consider switching to $fillable for stricter control in production.
     */
    protected $guarded = [];

    // Status constants (kept for convenience)
    public const STATUS_COMPLETED     = TripStatusEnum::COMPLETED;
    public const STATUS_NOT_COMPLETED = TripStatusEnum::NOT_COMPLETED;

    /**
     * Additional types metadata (kept as provided).
     * Consider naming as ADDITIONAL_TYPES for convention.
     */
    public const ADDITIONAL = [
        TripTypebBusEnum::START_DAY->value,
        TripTypebBusEnum::END_DAY->value,
    ];

    /**
     * Attribute casting
     * - latitude/longitude as float (they are decimals in DB; floats are convenient in PHP)
     * - status/trip_type are custom Enum casts you already have
     * - pivot.status cast to TripStudentStatusEnum (works when loading pivot via withPivot)
     */
    protected $casts = [
        'id'           => 'integer',
        'school_id'    => 'integer',
        'bus_id'       => 'integer',

        // Remove trips_date cast here since migration doesn't include it.
        // If you do have it in DB, uncomment the next line:
        // 'trips_date' => 'date',


        'trip_type'  => 'string',   // ✅ Native enum cast
        'status'     => TripStatusEnumCast::class, // ✅ Native enum cast

        'latitude'     => 'float',
        'longitude'    => 'float',

        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',

        // Cast pivot attribute (only when using ->withPivot('status', ...))
        'pivot.status' => TripStudentStatusEnum::class,
    ];
    protected $appends = ['has_days'];

    /* ======================
     | Relationships
     |======================*/

    public function bus()
    {
        // Trip belongs to Bus (nullable per migration)
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function school()
    {
        // Trip belongs to School (nullable per migration)
        return $this->belongsTo(School::class, 'school_id');
    }

    public function tripStudents()
    {
        return $this->hasMany(TripStudent::class, 'trip_id');
    }

    /**
     * Relationship: Trip has many Students (through TripStudent)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'trip_students', 'trip_id', 'student_id');
    }

    // public function attendants()
    // {
    //     // Many-to-many: trips <-> attendants through trip_attendants
    //     return $this->belongsToMany(Attendant::class, 'trip_attendants');
    // }
    public function attendants()
    {
        return $this->belongsToMany(Attendant::class, 'trip_attendants', 'trip_id', 'attendant_id');
    }

    public function attendances()
    {
        // One-to-many: a Trip has many Attendance records (FK: trip_id)
        return $this->hasMany(Attendance::class, 'trip_id');
    }
    public function tripDays()
    {
        // One-to-many: a Trip has many Attendance records (FK: trip_id)
        return $this->hasMany(TripDay::class, 'trip_id');
    }

    public function routes()
    {
        // One-to-many: a Trip has many TripRoute records (FK: trip_id)
        return $this->hasMany(TripRoute::class, 'trip_id');
    }

    public function creator()
    {
        /**
         * Polymorphic "created by" relation.
         * If each trip has a single creator, consider morphOne instead of morphMany.
         */
        return $this->morphMany(CreatedBy::class, 'creatable');
    }

    /* ======================
     | Accessors / Presenters
     |======================*/

    /**
     * Human-readable trip type (via enum)
     */
    public function tr_trip_type(): ?string
    {
        $trip_type = $this->trip_type; // enum instance from your TripTypeEnumCast
        return $trip_type?->getText();
    }

    /**
     * Status text/color (via enum)
     */
    public function tr_status(): array
    {
        $status = $this->status; // enum instance from your TripStatusEnumCast
        return [
            'text'  => $status?->getText(),
            'color' => $status?->getColor(),
        ];
    }

    /**
     * Convenience: grouped location accessor [lat, long] or null
     */
    public function getLocationAttribute(): ?array
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }
        return [$this->latitude, $this->longitude];
    }

    public function getHasDaysAttribute(): bool
    {
        if ($this->relationLoaded('tripDays')) {
            return $this->tripDays->isNotEmpty();
        }
        return $this->tripDays()->exists();
    }
    /* ======================
     | Query Scopes
     |======================*/

    /**
     * NOTE:
     * Your migration doesn't have trips_date.
     * The following "today/notCompleted/completed" scopes are rewritten to avoid using trips_date.
     * If you actually have trips_date in DB, uncomment the date filter blocks.
     */

    /**
     * Scope: only NOT_COMPLETED trips (optionally for today)
     */
    public function scopeNotCompleted($query)
    {
        return $query
            // ->whereDate('trips_date', Carbon::today()) // uncomment if column exists
            ->where('status', TripStatusEnum::NOT_COMPLETED->value);
    }

    /**
     * Scope: only COMPLETED trips (optionally for today)
     */
    public function scopeCompleted($query)
    {
        return $query
            // ->whereDate('trips_date', Carbon::today()) // uncomment if column exists
            ->where('status', TripStatusEnum::COMPLETED->value);
    }

    /**
     * Scope: "today" — left as a no-op since trips_date is not in migration.
     * If you add trips_date back, re-enable the filter.
     */
    public function scopeToday($query)
    {
        // $query->whereDate('trips_date', Carbon::today());
        return $query;
    }

    public function scopeStartDay($query)
    {
        return $query->where('trip_type', TripTypebBusEnum::START_DAY->value);
    }

    public function scopeEndDay($query)
    {
        return $query->where('trip_type', TripTypebBusEnum::END_DAY->value);
    }

    /**
     * Safe filter scope
     * - Use filled() to avoid notices
     * - Avoid "like" on enums unless you want partial matches; equality is safer for enum-backed values
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when(filled($filters['bus_id'] ?? null), function ($q) use ($filters) {
                $q->where('bus_id', $filters['bus_id']);
            })
            ->when(filled($filters['type'] ?? null), function ($q) use ($filters) {
                // If type corresponds exactly to enum value, prefer equality:
                $q->where('trip_type', $filters['type']);
                // If you intentionally want partial match, replace with:
                // $q->where('trip_type', 'like', '%' . $filters['type'] . '%');
            })
            ->when(filled($filters['date'] ?? null), function ($q) use ($filters) {
                // If you actually have trips_date column, uncomment:
                // $q->whereDate('trips_date', $filters['date']);
            });
    }
}
