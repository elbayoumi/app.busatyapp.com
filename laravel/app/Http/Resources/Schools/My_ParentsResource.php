<?php

namespace App\Http\Resources\Schools;

use Illuminate\Http\Resources\Json\JsonResource;

class My_ParentsResource extends JsonResource
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
            'email'         => $this->email,
            'name'          => $this->name,
            'phone'         => $this->phone,
            // 'school'        => $this->schools->name,
            'address'       => $this->address,
            'image'         => $this->logo_path,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),

        ];
    }
}


