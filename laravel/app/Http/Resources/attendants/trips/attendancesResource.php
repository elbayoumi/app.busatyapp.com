<?php

namespace App\Http\Resources\attendants\trips;

use Illuminate\Http\Resources\Json\JsonResource;

class attendancesResource extends JsonResource
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

            "id"                => $this->id,
            "school"       =>  $this->schools,
            "bus"          =>  $this->trip->bus,
            "driver"       =>  isset($this->trip->bus->driver) ? $this->trip->bus->driver: '',
            "admin"       =>  isset($this->trip->bus->admin) ? $this->trip->bus->admin: '',
            "attendence_date"   =>  $this->attendence_date,
            "trip"           =>  $this->trip,
            "type"              =>  $this->type,
            "attendence_status" =>  $this->attendence_status,
            "student"          =>  studentResource::make($this->students)

        ];
    }
}



// public function schools()
// {
//     return $this->belongsTo(School::class, 'school_id');
// }


// public function students()
// {
//     return $this->belongsTo(Student::class, 'student_id');
// }


// public function grade()
// {
//     return $this->belongsTo(Grade::class, 'grade_id');
// }

// public function trip()
// {
//     return $this->belongsTo(Trip::class,'trip_id');
// }
