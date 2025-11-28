<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'id' => $this->id,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'day' => $this->day?->value,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room' => $this->room,
            'academic_year' => $this->academic_year,
            'semester' => $this->semester,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
