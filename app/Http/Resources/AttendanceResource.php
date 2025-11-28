<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'student' => new StudentResource($this->whenLoaded('student')),
            'date' => $this->date?->format('Y-m-d'),
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'notes' => $this->notes,
            'photo' => $this->photo ? asset('storage/'.$this->photo) : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
