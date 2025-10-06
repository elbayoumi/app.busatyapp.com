<?php

namespace App\Http\Resources\Schools;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentsResource extends JsonResource
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
            'phone'         => isset($this->phone) ? $this->phone: null,
            'grade'         => isset($this->grade->name) ? $this->grade->name: null,
            'gender'        => isset($this->gender->name) ? $this->gender->name: null,
            'religion'      => isset($this->religion->name) ? $this->religion->name: null,
            'typeBlood'     => isset($this->typeBlood->name) ? $this->typeBlood->name: null,
            'school'        => $this->schools->name,
            'classroom'     => $this->classroom->name,
            'bus'           => isset($this->bus->name) ? $this->bus->name: null,
            'address'       => $this->address,
            'city_name'     => isset($this->city_name) ? $this->city_name: null,
            'Date_Birth'    => isset($this->Date_Birth) ? $this->Date_Birth: null,
            'key'           => $this->parent_key,
            'secret'        => $this->parent_secret,
            'image'         => $this->logo_path,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),

        ];
    }
}
