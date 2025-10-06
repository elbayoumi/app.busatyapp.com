<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * TripResource supports sparse fieldsets:
 * - fields[trip]=...
 * - fields[students]=...
 * - fields[bus]=...
 * - fields[attendants]=...
 */
class TripResource extends JsonResource
{
    /**
     * Filter an associative array to only requested keys (if any).
     */
    protected function onlyRequested(array $data, $request, string $groupKey): array
    {
        $requested = collect((array) $request->input("fields.$groupKey"))
            ->map(function ($v) {
                // Accept CSV string or array
                return is_string($v) ? explode(',', $v) : $v;
            })
            ->flatten()
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values();

        if ($requested->isEmpty()) {
            return $data; // nothing specified -> return full group
        }

        return collect($data)
            ->only($requested->all())
            ->all();
    }

    public function toArray($request)
    {
        // ---- Trip core (full set) ----
        $tripCore = [
            'id'           => $this->id,
            'school_id'    => $this->school_id,
            'name'         => $this->name,
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time,
            'days'         => $this->when(isset($this->days), $this->days),
            'exclude_days' => $this->when(isset($this->exclude_days), $this->exclude_days),
            'route_notes'  => $this->route_notes,
            'bus_id'       => $this->bus_id,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];

        // Apply fields[trip]=...
        $trip = $this->onlyRequested($tripCore, $request, 'trip');

        // ---- Relations (only if loaded) ----
        // Bus
        $bus = $this->whenLoaded('bus', function () use ($request) {
            $data = [
                'id'        => $this->bus->id,
                'plate'     => $this->bus->plate ?? null,
                'capacity'  => $this->bus->capacity ?? null,
                'driver_id' => $this->bus->driver_id ?? null,
            ];
            return $this->onlyRequested($data, $request, 'bus');
        });

        // Students
        $students = $this->whenLoaded('students', function () use ($request) {
            return $this->students->map(function ($s) use ($request) {
                $data = [
                    'id'    => $s->id,   // UUID
                    'name'  => $s->name ?? null,
                    'code'  => $s->code ?? null,
                    'grade' => $s->grade ?? null,
                    // add more student fields as needed
                ];
                return $this->onlyRequested($data, $request, 'students');
            });
        });

        // Attendants
        $attendants = $this->whenLoaded('attendants', function () use ($request) {
            return $this->attendants->map(function ($a) use ($request) {
                $data = [
                    'id'    => $a->id,
                    'name'  => $a->name ?? null,
                    'phone' => $a->phone ?? null,
                ];
                return $this->onlyRequested($data, $request, 'attendants');
            });
        });

        // Merge core + loaded relations
        return array_merge($trip, array_filter([
            'bus'        => $bus,
            'students'   => $students,
            'attendants' => $attendants,
        ], fn ($v) => !is_null($v)));
    }
}
