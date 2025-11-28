<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'name' => $this->name,
            'grade_level' => $this->grade_level,
            'major' => $this->major,
            'class_number' => $this->class_number,
            'academic_year' => $this->academic_year,
            'students_count' => $this->when(isset($this->students_count), $this->students_count),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
