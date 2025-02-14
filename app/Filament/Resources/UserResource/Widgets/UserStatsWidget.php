<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Enum\Status;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UserStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $startTime = now()->startOfDay();
        $endTime = now()->endOfDay();

        $stats = User::toBase()
            ->selectRaw("count(case when created_at >= ? and created_at < ? then 1 end) as users_today", [$startTime, $endTime])
            ->selectRaw("count(case when status = ? then 1 end) as under_review", [Status::UNDER_REVIEW->value])
            ->selectRaw("count(case when status = ? then 1 end) as approved", [Status::APPROVED->value])
            ->first();

        return [
            Stat::make('Registered today', $stats->users_today)
                ->description('Total user today'),

            Stat::make('Pending Approval', $stats->under_review)
                ->description('Total users under review'),

            Stat::make('Approved', $stats->approved)
                ->description('Total users approved'),
        ];
    }
}
