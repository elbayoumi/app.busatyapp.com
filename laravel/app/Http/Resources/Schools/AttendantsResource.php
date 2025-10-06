<?php

namespace App\Http\Resources\Schools;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendantsResource extends JsonResource
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
            'username'      => $this->username,
            'name'          => $this->name,
            'phone'         => $this->phone,
            'gender'        => isset($this->gender->name) ? $this->gender->name: null,
            'religion'      => isset($this->religion->name) ? $this->religion->name: null,
            'typeBlood'     => isset($this->typeBlood->name) ? $this->typeBlood->name: null,
            'school'        => $this->schools->name,
            'address'       => isset($this->bus->name) ? $this->bus->name: null ,
            'city_name'     => isset($this->address) ? $this->address: null,
            'birth_date'    => isset($this->birth_date) ? $this->birth_date: null,
            'image'         => $this->logo_path,
            'Joining_Date'  => isset($this->Joining_Date) ? $this->Joining_Date: null,
            'type'          => $this->type,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),

        ];
    }
}

