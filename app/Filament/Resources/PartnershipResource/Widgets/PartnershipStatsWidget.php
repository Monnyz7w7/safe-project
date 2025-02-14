<?php

namespace App\Filament\Resources\PartnershipResource\Widgets;

use App\Enum\Status;
use App\Models\Partnership;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PartnershipStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $startTime = now()->startOfDay();
        $endTime = now()->endOfDay();

        $stats = Partnership::toBase()
            ->selectRaw("count(case when created_at >= ? and created_at < ? then 1 end) as todays_report", [$startTime, $endTime])
            ->selectRaw("count(case when status = ? then 1 end) as under_review", [Status::UNDER_REVIEW->value])
            ->selectRaw("count(case when status = ? then 1 end) as approved", [Status::APPROVED->value])
            ->selectRaw("count(case when status = ? then 1 end) as declined", [Status::DECLINED->value])
            ->first();

        return [
            Stat::make('Today', $stats->todays_report)
                ->description('Total partnership today'),

            Stat::make('Under Review', $stats->under_review)
                ->description('Total partnership under review'),

            Stat::make('Approved', $stats->approved)
                ->description('Total partnership approved'),

            Stat::make('Declined', $stats->declined)
                ->description('Total partnership declined')
        ];
    }
}
