<?php

namespace App\Http\Resources\Parents\AttendantsMessages;

use Illuminate\Http\Resources\Json\JsonResource;

class studentMessagesResource extends JsonResource
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
            'name'                         => $this->name,
            'phone'                        => isset($this->phone) ? $this->phone: null,
            'grade'                        => isset($this->grade->name) ? $this->grade->name: null,
            'school'                       => isset($this->schools->name) ? $this->schools->name: null,
            'classroom'                    => isset($this->classroom->name) ? $this->classroom->name: null,
            'bus'                          => isset($this->bus->name) ? $this->bus->name: null,
            'logo_path'                    => isset($this->logo_path) ? $this->logo_path: null,

        ];
    }
}



