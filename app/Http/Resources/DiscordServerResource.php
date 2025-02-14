<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscordServerResource extends JsonResource
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
            'invitation_link' => $this->invitation_link,
            'registrant' => UserResource::make($this->whenLoaded('user')),
            'reports_count' => $this->when(isset($this->reports_count), $this->reports_count),
            'created_at' => DateTimeResource::make($this->created_at)
        ];
    }
}
