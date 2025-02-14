<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordLookUp
{
    public function find($userId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bot ' . config('services.discord.bot_token'),
            'Content-Type' => 'application/json',
        ])->get("https://discord.com/api/users/{$userId}");

        if (! $response->successful()) {
            return;
        }

        $data = $response->json();

        return [
            'id' => $data['id'] ?? null,
            'username' => $data['username'] ?? null,
            'global_name' => $data['global_name'] ?? null,
            'avatar_url' => $data['avatar'] ? "https://cdn.discordapp.com/avatars/{$data['id']}/{$data['avatar']}" : null,
        ];
    }
}
