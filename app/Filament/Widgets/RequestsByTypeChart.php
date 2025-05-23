<?php

namespace App\Filament\Widgets;

use App\Models\AccessRequest;
use App\Models\Department;
use App\Models\RequestType;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Str;

class RequestsByTypeChart extends ChartWidget
{

    protected static ?int $sort = 5;

    protected static ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return 'Solicitudes x Tipo Actividad';
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
                        'text' => 'Meses'
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
        $dataSets = [];
        RequestType::query()->get()->map(function (RequestType $type) use (&$dataSets) {
            $data = Trend::query(AccessRequest::query()->where('request_type_id', $type->id))
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count();
            $color = Color::all()[array_rand(Color::all())][500];
            $dataSets[] = [
                'label' => $type->name,
                'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                'borderColor' => "rgb({$color})",
                'backgroundColor' => "rgb({$color})",
            ];
        });

        return [
            'datasets' => $dataSets,
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        ];
    }
}
