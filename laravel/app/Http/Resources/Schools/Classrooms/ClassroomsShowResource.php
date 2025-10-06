<?php

namespace App\Http\Resources\Schools\Classrooms;

use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomsShowResource extends JsonResource
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
            'school'        => $this->school->name,
            'create_date'   => $this->created_at->format('d-m-Y h:i a'),
            'students'      => ClassroomstudentsResource::collection($this->students),
            'grade_name'    => $this->grade->name,
            'grade'         => $this->grade,
            'students_count' =>  $this->students->count(),

        ];
    }
}

