<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\DiscordServer;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscordServerResource;

class DiscordServerController extends Controller
{
    public function index()
    {
        return DiscordServerResource::collection(
            DiscordServer::with('user')
                ->withCount('reports')
                ->paginate(50)
        );
    }

    public function show(DiscordServer $discordServer)
    {
        $discordServer = DiscordServer::with('user')->withCount('reports')->findOrFail($discordServer->id);

        return DiscordServerResource::make($discordServer);
    }
}
