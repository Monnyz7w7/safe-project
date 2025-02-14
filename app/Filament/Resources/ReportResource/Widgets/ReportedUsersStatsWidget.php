<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Report;
use App\Enum\Status;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ReportedUsersStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $startTime = now()->startOfDay();
        $endTime = now()->endOfDay();

        $reportStats = Report::toBase()
            ->selectRaw("count(case when created_at >= ? and created_at < ? then 1 end) as todays_report", [$startTime, $endTime])
            ->selectRaw("count(case when status = ? then 1 end) as under_review", [Status::UNDER_REVIEW->value])
            ->selectRaw("count(case when status = ? then 1 end) as approved", [Status::APPROVED->value])
            ->selectRaw("count(case when status = ? then 1 end) as declined", [Status::DECLINED->value])
            ->first();

        return [
            Stat::make('Today\'s Report', $reportStats->todays_report)
                ->description('Total reports today'),

            Stat::make('Under Review', $reportStats->under_review)
                ->description('Total reports under review'),

            Stat::make('Approved', $reportStats->approved)
                ->description('Total reports approved'),

            Stat::make('Declined', $reportStats->declined)
                ->description('Total reports declined')
        ];
    }
}
