<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Services\DiscordLookUp;
use Illuminate\Support\Facades\Validator;

class FetchDiscordUserInfoController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|min:17|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        return DiscordLookUp::find($request->user_id);
    }
}
