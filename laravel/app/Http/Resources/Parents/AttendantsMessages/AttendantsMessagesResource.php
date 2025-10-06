<?php

namespace App\Http\Resources\Parents\AttendantsMessages;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendantsMessagesResource extends JsonResource
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
            'school_id'                    => isset($this->attendant->schools->id) ? $this->attendant->schools->id: null,
            'my__parent_id'                => $this->my__parent_id,
            'student_id'                   => $this->student_id,
            'status'                       => $this->status,
            'create_date'                  => $this->created_at,
            'to'                           => isset($this->parents) ? $this->parents: null,
            'from'                         => isset($this->attendant) ? $this->attendant: null,
            'message'                      => isset($this->static_message->message) ? $this->static_message->message: null,
            'student'                      => isset($this->student) ? studentMessagesResource::make($this->student): null,
        ];
    }
}

