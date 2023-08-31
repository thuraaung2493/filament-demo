<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Patient;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

final class PatientsChart extends ChartWidget
{
    protected static ?string $heading = 'Patients';

    protected function getData(): array
    {
        $dogs = Trend::query(
            Patient::query()->where('type', 'dog')
        )
            ->between(
                start: \now()->subYear(),
                end: \now(),
            )
            ->perMonth()
            ->count();

        $cats = Trend::query(
            Patient::query()->where('type', 'cat')
        )
            ->between(
                start: \now()->subYear(),
                end: \now(),
            )
            ->perMonth()
            ->count();

        $rabbits = Trend::query(
            Patient::query()->where('type', 'rabbit')
        )
            ->between(
                start: \now()->subYear(),
                end: \now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Dogs',
                    'data' => $dogs->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(75, 192, 192)',
                ],
                [
                    'label' => 'Cats',
                    'data' => $cats->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(255, 14, 24)',
                ],
                [
                    'label' => 'Rabbits',
                    'data' => $rabbits->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(120, 255, 150)',
                ],
            ],
            'labels' => $dogs->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
