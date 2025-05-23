<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class ContractsChart extends ChartWidget
{
    protected static ?string $heading = 'Contratos por Departamentos';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',

            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'datalabels' => [
                    'anchor' => 'start',
                    'align' => 'start',
                    'color' => 'white',
                    'font' => [
                        'weight' => 'bold',
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {

        $contract = Department::query()->withCount('contracts')->get();
        $data = $contract->map(fn(Department $value) => $value->contracts_count);
        $labels = $contract->map(fn(Department $value) => $value->name);

        return [
            'datasets' => [
                [
                    'label' => 'Contratos',
                    'data' => $data,
                    'backgroundColor' => '#04d53bb3',
                    'borderColor' => '#04d53bb3',
                    'borderWidth' => 1,
                    'fill' => false,
                ]
            ],
            'labels' => $labels,
        ];
    }
}
