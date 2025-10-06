<?php

namespace App\Http\Resources\Schools\Grade;

use Illuminate\Http\Resources\Json\JsonResource;

class GradestudentsResource extends JsonResource
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
            'phone'         => $this->phone,
            'grade'         => $this->grade->name,
            'gender'        => $this->gender->name,
            'religion'      => $this->religion->name,
            'typeBlood'     => $this->typeBlood->name,
            'school'        => $this->schools->name,
            'classroom'     => $this->classroom->name,
            'bus'           => $this->bus->name,
            'address'       => $this->address,
            'city_name'     => $this->city_name,
            'Date_Birth'    => $this->Date_Birth,
            'key'           => $this->parent_key,
            'secret'        => $this->parent_secret,
            'image'         => $this->logo_path,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),

        ];
    }
}

