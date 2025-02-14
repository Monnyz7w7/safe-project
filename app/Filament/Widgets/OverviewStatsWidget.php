<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Report;
use App\Models\Partnership;
use App\Models\Sponsorship;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $startTime = now()->startOfDay();
        $endTime = now()->endOfDay();

        $reportStats = $this->countEntitiesBetweenDates(Report::class, $startTime, $endTime, 'todays_report');
        $userStats = $this->countEntitiesBetweenDates(User::class, $startTime, $endTime, 'todays_users');
        $partnershipStats = $this->countEntitiesBetweenDates(Partnership::class, $startTime, $endTime, 'todays_partnerships');
        $sponsorshipsStats = $this->countEntitiesBetweenDates(Sponsorship::class, $startTime, $endTime, 'todays_sponsorships');

        return [
            Stat::make('Today\'s Report', $reportStats->todays_report)
                ->description('Total reports today'),

            Stat::make('Today\'s Users', $userStats->todays_users)
                ->description('Total users today'),

            Stat::make('Today\'s Partnerships', $partnershipStats->todays_partnerships)
                ->description('Total partnerships today'),

            Stat::make('Today\'s Sponsorships', $sponsorshipsStats->todays_sponsorships)
                ->description('Total sponsorships today'),
        ];
    }

    protected function countEntitiesBetweenDates($model, $startTime, $endTime, $alias)
    {
        return $model::toBase()
            ->selectRaw("count(case when created_at >= ? and created_at < ? then 1 end) as $alias", [$startTime, $endTime])
            ->first();
    }
}
