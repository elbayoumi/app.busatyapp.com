<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->notification->id,
            'body' => (string) $this->notification->data['body'] ?? __("data not found"),
            'from' => (string) $this->notification->data['from'] ?? __("data not found"),
            'type' => (string) $this->notification->data['type'] ?? __("data not found"),
            'title' => (string) $this->notification->data['title'] ?? __("data not found"),
            'additional' => (array) $this->notification->data['additional'] ?? [__("data not found")],
            'read_at' => $this->read_at,
            'created_at' => $this->notification->created_at,
            'updated_at' => $this->notification->updated_at,
        ];
    }
}
