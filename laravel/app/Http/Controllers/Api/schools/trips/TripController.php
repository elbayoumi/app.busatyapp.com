<?php

namespace App\Http\Controllers\Api\schools\trips;

use App\Enum\TripTypebBusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\AssignBusRequest;
use App\Http\Requests\Trip\AttachAttendantsRequest;
use App\Http\Requests\Trip\AttachStudentsRequest;
use App\Http\Requests\Trip\StoreTripRequest;
use App\Http\Requests\Trip\TransferStudentRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\Trip\UpdateTripRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class TripController extends Controller
{
    /**
     * List trips with optional includes and sparse fieldsets.
     * Supported query params:
     * - include=students,bus,attendants
     * - fields[trip]=id,name,start_time
     * - fields[students]=id,name
     * - fields[bus]=id,plate
     * - fields[attendants]=id,name,phone
     * - school_id=76 (filter)
     * - q=Morning (search by name)
     * - per_page=20&page=1 (pagination)
     * - sort=-created_at,name  (multi-sort; prefix '-' for desc)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Whitelisted includes
        $allowedIncludes = ['students', 'bus', 'attendants'];

        // Parse includes safely: ?include=students,bus
        $include = collect(explode(',', (string) $request->query('include')))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->intersect($allowedIncludes)
            ->values()
            ->all();

        // Base query scoped to the authenticated school
        $query = $user->trips(); // same as Trip::where('school_id', $user->id)

        // Filters
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->integer('school_id'));
        }

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%");
            });
        }

        // Sorting: ?sort=-created_at,name
        if ($request->filled('sort')) {
            $sorts = explode(',', (string) $request->query('sort', ''));
            foreach ($sorts as $sort) {
                $sort = trim($sort);
                if ($sort === '') continue;

                $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
                $column    = ltrim($sort, '-');

                // Whitelist to prevent SQL injection
                if (in_array($column, ['id', 'name', 'created_at', 'start_time', 'end_time'], true)) {
                    $query->orderBy($column, $direction);
                }
            }
        } else {
            $query->latest('id');
        }

        // Eager load only requested relations
        if (!empty($include)) {
            $query->with($include);
        }

        // Pagination with clamp
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));
        $paginator = $query->paginate($perPage)->appends($request->query());

        // Keep the same response shape
        return JSON([
            'trips' => TripResource::collection($paginator->items()),
            'meta'  => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'prev'  => $paginator->previousPageUrl(),
                'next'  => $paginator->nextPageUrl(),
                'last'  => $paginator->url($paginator->lastPage()),
            ],
        ]);
    }

    /**
     * Return a single trip with its related data.
     * Supports ?include=students,bus,attendants (defaults to all).
     */
    public function show(Request $request, Trip $trip)
    {
        $allowedIncludes = ['students', 'bus', 'attendants'];

        $include = collect(explode(',', (string)$request->query('include')))
            ->map(fn($s) => trim($s))
            ->filter()
            ->intersect($allowedIncludes)
            ->values()
            ->all();

        if (empty($include)) {
            // Load nothing by default; client explicitly asks for what they want
            $include = [];
        }

        $trip->load($include);

        return JSON(new TripResource($trip));
    }
    /**
     * Store a new trip (optionally attach bus, students, and attendants).
     * Atomic with DB transaction.
     */


    public function store(StoreTripRequest $request)
    {
        $school = $request->user();

        return DB::transaction(function () use ($request, $school) {
            // return $request->all();
            $trip = new Trip();
            $trip->school_id = $school->id;

            // IMPORTANT: cast Stringable -> string
            $trip->name = $request->string('name')->trim()->toString();
            $trip->trip_type = $request->string('trip_type')->trim()->toString();

            $trip->save();

            if ($request->filled('bus_id')) {
                $trip->bus_id = $request->integer('bus_id');
                $trip->save();
            }

            if ($request->filled('attendants')) {
                $trip->attendants()->syncWithoutDetaching($request->input('attendants'));
            }

            $trip->load(['bus', 'attendants']);

            return JSON([
                'data' => $trip,
                'message' => 'Trip created successfully',
                'status' => true,
            ], 201);
        });
    }


    /**
     * Assign or change trip bus.
     */
    public function assignBus(AssignBusRequest $request, Trip $trip)
    {

        // Assign the new bus using mass update for clarity
        $trip->update([
            'bus_id' => $request->integer('bus_id'),
        ]);

        // Reload relations to return fresh data
        $trip->load('bus');

        return JSON([
            'trip'    => $trip,
        ]);
    }


    /**
     * Attach or sync students to a trip.
     * mode=attach -> keep existing and add new (default)
     * mode=sync   -> replace existing with the provided list
     */
    public function attachStudents(AttachStudentsRequest $request, Trip $trip)
    {
        $studentIds = $request->input('students', []);
        $mode = $request->input('mode', 'attach');

        if ($mode === 'sync') {
            // Replace with provided set (IDs are UUIDs)
            $trip->students()->sync($studentIds);
        } else {
            // Add without removing, no duplicates
            $trip->students()->syncWithoutDetaching($studentIds);
        }

        $trip->load('students');

        return JSON([
            'trip' => $trip,
        ]);
    }

    /**
     * Attach or sync attendants (supervisors) to a trip.
     */
    public function attachAttendants(AttachAttendantsRequest $request, Trip $trip)
    {
        $attendantIds = $request->input('attendants', []);
        $mode = $request->input('mode', 'attach');

        if ($mode === 'sync') {
            $trip->attendants()->sync($attendantIds);
        } else {
            $trip->attendants()->syncWithoutDetaching($attendantIds);
        }

        $trip->load('attendants');

        return JSON([
            'trip' => $trip,
        ]);
    }
    /**
     * Update trip attributes (name, trip_type, bus_id ...).
     * AuthZ hint: make sure the school owns the trip.
     */
    /**
     * Update trip attributes (name, trip_type, bus_id, start_time, end_time).
     * Does NOT change the response format or logic.
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        // Update only fields that were actually sent
        if ($request->filled('name')) {
            $trip->name = $request->string('name')->trim()->toString();
        }

        if ($request->filled('trip_type')) {
            $trip->trip_type = $request->string('trip_type')->trim()->toString(); // start_day | end_day
        }

        // Allow null to unassign the bus
        $busTouched = false;
        if ($request->exists('bus_id')) {
            $trip->bus_id = $request->input('bus_id'); // nullable|integer|exists:buses,id
            $busTouched = true;
        }

        $trip->save();

        /**
         * Relation syncing semantics:
         * - If the key exists in the request, SYNC exactly to what was sent.
         * - Sending [] clears all.
         * - If the key is NOT present, leave the relation untouched.
         */
        $mustLoad = [];

        if ($request->has('attendants')) {
            $attendants = (array) $request->input('attendants', []);
            $trip->attendants()->sync($attendants);
            $mustLoad[] = 'attendants';
        }

        // Optional students sync (kept commented to match your current behavior)
        // if ($request->has('students')) {
        //     $students = (array) $request->input('students', []);
        //     $trip->students()->sync($students);
        //     $mustLoad[] = 'students';
        // }

        if ($busTouched) {
            $mustLoad[] = 'bus';
        }

        // Merge forced loads with optional ?include=...
        $allowedIncludes = ['bus', 'attendants', 'students'];
        $queryIncludes = collect(explode(',', (string) $request->query('include')))
            ->map(fn($s) => trim($s))
            ->filter()
            ->intersect($allowedIncludes)
            ->values()
            ->all();

        $relationsToLoad = collect($mustLoad)->merge($queryIncludes)->unique()->values()->all();

        if (!empty($relationsToLoad)) {
            // Prevent stale relations, then load fresh ones
            foreach ($relationsToLoad as $rel) {
                $trip->unsetRelation($rel);
            }
            $trip->load($relationsToLoad);
        }

        return JSON([
            'data'    => $trip,
            'message' => 'Trip updated successfully',
            'status'  => true,
        ]);
    }




    /**
     * Delete trip (cascades will handle pivots).
     */
    public function destroy(Request $request, Trip $trip)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        DB::transaction(function () use ($trip) {
            $trip->delete();
        });

        return JSON([
            'message' => 'Trip deleted successfully',
            'status'  => true,
        ], 200);
    }

    /**
     * Detach a single student from the trip.
     */
    public function detachStudent(Request $request, Trip $trip, string $studentId)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        $trip->students()->detach($studentId);

        // Return minimal payload; or load('students') لو عايز
        return JSON([
            'message' => 'Student detached from trip',
            'status'  => true,
        ]);
    }

    /**
     * Detach multiple students in one call.
     * Body: { "students": ["uuid1","uuid2", ...] }
     */
    public function detachStudents(Request $request, Trip $trip)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        $ids = (array) $request->input('students', []);
        if (empty($ids)) {
            return JSON(['message' => 'No students provided', 'status' => false], 422);
        }

        $trip->students()->detach($ids);

        return JSON([
            'message' => 'Students detached from trip',
            'status'  => true,
        ]);
    }

    /**
     * Detach a single attendant from the trip.
     */
    public function detachAttendant(Request $request, Trip $trip, int $attendantId)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        $trip->attendants()->detach($attendantId);

        return JSON([
            'message' => 'Attendant detached from trip',
            'status'  => true,
        ]);
    }

    /**
     * Detach multiple attendants in one call.
     * Body: { "attendants": [1,2,3] }
     */
    public function detachAttendants(Request $request, Trip $trip)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        $ids = (array) $request->input('attendants', []);
        if (empty($ids)) {
            return JSON(['message' => 'No attendants provided', 'status' => false], 422);
        }

        $trip->attendants()->detach($ids);

        return JSON([
            'message' => 'Attendants detached from trip',
            'status'  => true,
        ]);
    }

    /**
     * Unassign the bus from a trip (set bus_id = null).
     */
    public function unassignBus(Request $request, Trip $trip)
    {
        if ((int)$request->user()->id !== (int)$trip->school_id) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        $trip->update(['bus_id' => null]);

        return JSON([
            'message' => 'Bus unassigned from trip',
            'status'  => true,
        ]);
    }


    public function transferStudent(TransferStudentRequest $request)
    {
        // Extract validated inputs (already validated by TransferStudentRequest)
        $studentId  = (string) $request->input('student_id');
        $fromTripId = (int) $request->input('from_trip_id');
        $toTripId   = (int) $request->input('to_trip_id');

        // Load source/target trips
        $from = Trip::query()->findOrFail($fromTripId);
        $to   = Trip::query()->findOrFail($toTripId);

        // Ownership: both trips must belong to the authenticated school
        $schoolId = (int) $request->user()->id;
        if ((int) $from->school_id !== $schoolId || (int) $to->school_id !== $schoolId) {
            return JSON(['message' => 'Forbidden', 'status' => false], 403);
        }

        // Ensure the student currently belongs to the source trip
        $isInSource = $from->students()->whereKey($studentId)->exists();
        if (! $isInSource) {
            return JSON([
                'message' => 'Student is not attached to the source trip.',
                'status'  => false,
            ], 409);
        }

        // Atomic transfer
        DB::transaction(function () use ($from, $to, $studentId) {
            $from->students()->detach($studentId);
            $to->students()->syncWithoutDetaching([$studentId]);
        });

        // Optional includes: ?include=students,bus,attendants
        $allowedIncludes = ['students', 'bus', 'attendants'];
        $include = collect(explode(',', (string) $request->query('include')))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->intersect($allowedIncludes)
            ->values()
            ->all();

        if (!empty($include)) {
            // Return fresh relations on the target trip
            foreach ($include as $rel) {
                $to->unsetRelation($rel);
            }
            $to->load($include);
        }

        return JSON([
            'data' => [
                'from_trip_id' => $from->id,
                'to_trip_id'   => $to->id,
                'student_id'   => $studentId,
                'to_trip'      => !empty($include) ? $to : null, // included only if requested
            ],
            'message' => 'Student transferred successfully',
            'status'  => true,
        ]);
    }


}
