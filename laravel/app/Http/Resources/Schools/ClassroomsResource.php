<?php

namespace App\Http\Resources\Schools;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomsResource extends JsonResource
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
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),

        ];
    }
}

