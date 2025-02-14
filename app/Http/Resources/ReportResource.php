<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'username' => $this->discord_username,
            'user_id' => $this->discord_user_id,
            'global_name' => $this->discord_user_global_name,
            'avatar_url' => $this->discord_user_avatar_url,
            'details' => $this->details,
            'status' => $this->status,
            'reported_by' => UserResource::make($this->whenLoaded('user')),
            'server' => DiscordServerResource::make($this->whenLoaded('discordServer')),
            'action_at' => DateTimeResource::make($this->action_at),
            'initiator' => UserResource::make($this->whenLoaded('actionBy')),
            'created_at' => DateTimeResource::make($this->created_at)
        ];
    }
}
