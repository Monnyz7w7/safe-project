<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use App\Services\StoreImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        return ReportResource::collection(Report::with('actionBy', 'user', 'discordServer')->latest('id')->paginate(50));
    }

    public function show(Report $report)
    {
        return ReportResource::make($report->load('actionBy', 'user', 'discordServer'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discord_user_id' => 'required|min:17|max:255',
            'discord_username' => 'required|string|min:4|max:255',
            'discord_user_global_name' => 'string|nullable',
            'discord_user_avatar_url' => 'required|string|url|max:255',
            'discord_server_id' => 'required|exists:discord_servers,id',
            'details' => 'required|min:10|max:80000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $report = auth()->user()->reports()->create($request->all() + [
            'discord_user_avatar_url' => StoreImage::save($request->discord_user_avatar_url)
        ]);

        return ReportResource::make($report->load('actionBy', 'user', 'discordServer'));
    }
}
