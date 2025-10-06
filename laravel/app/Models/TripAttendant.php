<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripAttendant extends Model
{
    // Table name (Laravel will automatically detect "trip_attendants", so this is optional)
    protected $table = 'trip_attendants';

    // Disable timestamps because the migration does not include them
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'trip_id',
        'attendant_id',
    ];

    // -----------------------
    // Relationships
    // -----------------------

    /**
     * Get the trip that this record belongs to.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the attendant (student/guardian/staff) linked to this trip.
     */
    public function attendant(): BelongsTo
    {
        return $this->belongsTo(Attendant::class);
    }
}
