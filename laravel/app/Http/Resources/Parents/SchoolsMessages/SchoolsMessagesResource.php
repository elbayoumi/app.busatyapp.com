<?php

namespace App\Http\Resources\Parents\SchoolsMessages;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsMessagesResource extends JsonResource
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
            'school_id'                    => isset($this->school_messages->schools->id) ? $this->school_messages->schools->id: null,
            'my__parent_id'                => $this->my__parent_id,
            'school_messages_id'           => $this->school_messages_id,
            'status'                       => $this->status,
            'create_date'                  => $this->created_at,
            'to'                           => isset($this->parents) ? $this->parents: null,
            'from'                         => isset($this->school_messages->schools) ? $this->school_messages->schools: null,
            'title'                        => isset($this->school_messages->name) ? $this->school_messages->name: null,
            'message'                      => isset($this->school_messages->message) ? $this->school_messages->message: null,
            'event_date'                   => isset($this->school_messages->event_date) ? $this->school_messages->event_date: null,

        ];
    }
}


// "id": 1,
// "my__parent_id": 1,
// "status": 0,
// "school_messages_id": 1,
// "created_at": null,
// "updated_at": null,
// "school_messages": {
//     "id": 1,
//     "school_id": 10,
//     "message": "Ea at voluptas et al",
//     "name": "Jeremy Alexander",
//     "event_date": "2022-10-24",
//     "created_at": "2022-10-23T05:59:14.000000Z",
//     "updated_at": "2022-10-23T05:59:14.000000Z"
// }
