<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'discord_username' => $this->discord_username,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'status' => $this->status,
            'action_at' => $this->when(
                $this->relationLoaded('actionBy'),
                DateTimeResource::make($this->action_at)
            ),
            'initiator' => UserResource::make($this->whenLoaded('actionBy')),
            'created_at' => DateTimeResource::make($this->created_at)
        ];
    }
}
