<?php

namespace App\Http\Resources\attendants\trips;

use Illuminate\Http\Resources\Json\JsonResource;

class studentResource extends JsonResource
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


            "id"          => $this->id,
            "name"        => isset($this->name) ? $this->name : null,
            "phone"       => isset($this->phone) ? $this->phone : null,
            "grade"       => isset($this->grade) ? $this->grade : null,
            "gender"      => isset($this->gender) ? $this->gender : null,
            "school"      => $this->schools->name,
            "religion"    => isset($this->religion) ? $this->religion : null,
            "classroom"   => $this->classroom,
            "address"     => isset($this->address) ? $this->address : null,
            "city_name"   => isset($this->city_name) ? $this->city_name : null,
            "trip_type"   => isset($this->trip_type) ? $this->trip_type : null,
            "date_birth"  => isset($this->Date_Birth) ? $this->Date_Birth : null,
            "logo_path"   => $this->logo_path,
            "parents"     => parentsResource::collection($this->my_Parents)

        ];
    }
}






