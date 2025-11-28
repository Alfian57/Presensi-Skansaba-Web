<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'nisn' => $this->nisn,
            'nis' => $this->nis,
            'gender' => $this->gender?->value,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'phone' => $this->phone,
            'address' => $this->address,
            'entry_year' => $this->entry_year,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'parent_name' => $this->parent_name,
            'parent_phone' => $this->parent_phone,
            'has_active_device' => ! is_null($this->active_device_id),
            'device_registered_at' => $this->device_registered_at?->toISOString(),
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
