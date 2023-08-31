<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Owner;
use App\Models\Treatment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class TreatmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Treatments';

    protected function getData(): array
    {
        $data = Trend::model(Treatment::class)
            ->between(
                start: \now()->subYear(),
                end: \now(),
            )
            ->perMonth()
            ->count();

        $ownerData = Trend::model(Owner::class)
            ->between(
                start: \now()->subYear(),
                end: \now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Treatments',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(75, 192, 192)',
                ],
                [
                    'label' => 'Owners',
                    'data' => $ownerData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(255, 14, 24)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
