<?php

namespace App\Http\Resources\attendants\trips;

use Illuminate\Http\Resources\Json\JsonResource;

class absencesResource extends JsonResource
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
            "school_name"       =>  $this->schools->name,
            "bus_name"          =>  $this->bus->name,
            "student_name"      =>  $this->students->name,
            "student_grade"     =>  $this->students->grade->name,
            "student_classroom" =>  $this->students->classroom->name,
            "attendence_date"   =>  $this->attendence_date,
            "attendence_type"   =>   attendence_type($this->attendence_type),
            "parent"            => parentsResource::make($this->parent)


        ];
    }
}



