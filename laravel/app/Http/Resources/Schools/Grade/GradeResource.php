<?php

namespace App\Http\Resources\Schools\Grade;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'create_date'   => $this->created_at->format('Y-m-d'),
            'school'        =>  $request->user()->name,
            'classroom'     => GradeClassroomResource::collection(Classroom::where('grade_id', $this->id)->where('school_id', $request->user()->id)->get()),
            'students'      => GradestudentsResource::collection(Student::where('grade_id', $this->id)->where('school_id', $request->user()->id)->get()),

        ];
    }
}

