<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentWaitingResource extends JsonResource
{
    public function toArray($request)
    {
        $hasCurrent = $this->temporaryAddresses->contains(function ($address) {
            return $address->is_current;
        });

        $temporaryAddresses = $this->temporaryAddresses->isNotEmpty()
            ? $this->temporaryAddresses->map(function ($address) {
                return [
                    'id' => $address->id,
                    'student_id' => $address->student_id,
                    'address' => $address->address,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'from_date' => $address->from_date,
                    'to_date' => $address->to_date,
                    'accept_status' => $address->accept_status,
                    'created_at' => $address->created_at,
                    'updated_at' => $address->updated_at,
                    'is_current' => $address->is_current,
                ];
            })
            : null;

        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'trip_type' => $this->trip_type,
            'school_id' => $this->school_id,
            'bus_id' => $this->bus_id,
            'logo_path' => $this->logo_path,
            'has_current_address' => $hasCurrent,

            'my__parents' => $this->whenLoaded('my__parents') ?? [],
            // 'temporary_addresses' => $temporaryAddresses,
        ], fn($v) => !is_null($v));
    }
}
