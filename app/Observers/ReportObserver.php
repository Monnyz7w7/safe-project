<?php

namespace App\Observers;

use App\Models\Report;
use App\Services\StoreImage;

class ReportObserver
{
    public function creating(Report $report): void
    {
        $report->discord_user_avatar_url = StoreImage::save($report->discord_user_avatar_url);
    }
}
