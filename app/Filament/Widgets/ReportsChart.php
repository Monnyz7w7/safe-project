<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ReportsChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Total reports per month';

    protected function getData(): array
    {
        $data = Trend::model(Report::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Reported Users',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(251, 146, 60, 0.2)',
                    'borderColor' => '#fb923c',
                    'barPercentage' => 0.8
                ],
            ],
            'labels' => $this->getMonthlyLabels(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getMonthlyLabels(): array
    {
        return collect(range(1, 12))
            ->map(fn ($month) => now()->startOfYear()->addMonths($month - 1)->format('M'))
            ->toArray();
    }
}
