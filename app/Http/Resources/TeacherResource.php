<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'employee_number' => $this->employee_number,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'gender' => $this->gender?->value,
            'phone' => $this->phone,
            'address' => $this->address,
            'hire_date' => $this->hire_date?->format('Y-m-d'),
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
