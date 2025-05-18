<?php

namespace App\Filament\Resources\CommercialUnitResource\Widgets;

use App\Models\CommercialUnit;
use App\Models\UnitScore;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class CommercialUnitChart extends ChartWidget
{
    protected static ?string $heading = 'Calificaciones';

    public ?Model $record = null;

    protected function getData(): array
    {

        $this->record->load('scores.criteria');
        $variableData = $this->record->scores->map(fn (UnitScore $value) => $value->criteria->max_score);
        $scoreData = $this->record->scores->map(fn (UnitScore $value) => $value->score);
        $labels = $this->record->scores->map(fn (UnitScore $value) => $value->criteria->name);

        return [
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => $variableData,
                    'fill' => true,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'pointBackgroundColor' => 'rgb(255, 99, 132)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(255, 99, 132)',
                ],
                [
                    'label' => 'Calificación',
                    'data' => $scoreData,
                    'fill' => true,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'pointBackgroundColor' => 'rgb(54, 162, 235)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $labels
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
