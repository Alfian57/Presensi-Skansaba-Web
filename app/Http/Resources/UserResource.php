<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'username' => $this->username,
            'photo' => $this->photo ? asset('storage/'.$this->photo) : null,
            'role' => $this->whenLoaded('roles', function () {
                return $this->roles->first()?->name;
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
