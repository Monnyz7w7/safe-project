<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class StoreImage
{
    public static function save(string $imageUrl): ?string
    {
        $response = Http::get($imageUrl);

        if (! $response->successful()) {
            return null;
        }

        $imageContent = $response->body();

        $imageFileName = pathinfo(basename($imageUrl), PATHINFO_FILENAME) . '.jpg';

        Storage::disk('public')->put($imageFileName, $imageContent);

        return $imageFileName;
    }
}

