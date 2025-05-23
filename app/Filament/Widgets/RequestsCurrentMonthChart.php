<?php

namespace App\Filament\Widgets;

use App\Models\AccessRequest;
use App\Models\Department;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Str;

class RequestsCurrentMonthChart extends ChartWidget
{

    protected static ?int $sort = 4;

    protected static ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return 'Solicitudes: ' . Str::ucfirst(now()->translatedFormat('F'));
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'datalabels' => ['display' => false]
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Días'
                    ]
                ],
                'y' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Cantidad'
                    ]
                ],
            ]
        ];
    }

    protected function getData(): array
    {

        $data = Trend::model(AccessRequest::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Solicitudes',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => 'start',
                ],

            ],
            'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat('d'))
        ];
    }
}
