<?php

namespace App\Filament\Widgets;

use App\Enum\Status;
use App\Models\Report;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ApprovedReportsChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Approved reports per month';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = Trend::query(Report::query()->whereStatus(Status::APPROVED))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Approved Reports',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(45, 212, 191, 0.2)',
                    'borderColor' => '#2dd4bf',
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
