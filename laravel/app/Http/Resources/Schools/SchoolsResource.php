<?php

namespace App\Http\Resources\Schools;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'status'        => $this->status,
            'address'       => $this->address,
            'city_name'     => $this->city_name,
            'logo'          => $this->logo_path,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),
            'grades'        => GradesResource::collection($this->grades),
            'classrooms'    => ClassroomsResource::collection($this->classrooms),
            'buses'         => BusesResource::collection($this->buses),
            'Students'      => StudentsResource::collection($this->students),
            'attendants'    => AttendantsResource::collection($this->attendants),
            'fixedTrip'    => FixedTripsResource::collection($this->fixedTrip),
            // 'parents'       => My_ParentsResource::collection($this->parents),
        ];
    }
}

