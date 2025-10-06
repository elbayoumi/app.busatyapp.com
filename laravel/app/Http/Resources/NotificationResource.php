<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'current_page' => $this->currentPage(),
            'data' => $this->items(),
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage(),
        ];
    }
}
