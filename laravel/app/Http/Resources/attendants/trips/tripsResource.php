<?php

namespace App\Http\Resources\attendants\trips;

use Illuminate\Http\Resources\Json\JsonResource;

class tripsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                           => $this->id,
            'school'                       => $this->school,
            'bus'                          => isset($this->bus) ? $this->bus: 'Undefined',
            'driver'                       => isset($this->bus->driver) ? $this->bus->driver: 'Undefined',
            'admin'                        => isset($this->bus->admin) ? $this->bus->admin: 'Undefined',
            'trips_date'                   => $this->trips_date,
            'trip_type'                    => $this->trip_type,
            'status'                       => $this->status,
            'create_date'                  => $this->created_at->format('Y-m-d'),
            'attendances'                  => isset($this->attendances) ? attendancesResource::collection($this->attendances): [],
            // 'students_bus_trip'            => isset($this->bus->students) ? $this->bus->students: 'Undefined',

        ];
    }
}


