<?php

namespace App\Filament\Resources\CommercialUnitResource\Widgets;

use App\Models\CommercialUnit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommercialUnitStats extends BaseWidget
{

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $total = CommercialUnit::query()->count();
        $countSuccess = CommercialUnit::query()->whereHas('scores', function ($query) {
            $query->select(\Illuminate\Support\Facades\DB::raw('commercial_unit_id'))
                ->groupBy('commercial_unit_id')
                ->havingRaw('SUM(score) BETWEEN ? AND ?', [18, 20]);
        })->count();
        $countWarning = CommercialUnit::query()->whereHas('scores', function ($query) {
            $query->select(\Illuminate\Support\Facades\DB::raw('commercial_unit_id'))
                ->groupBy('commercial_unit_id')
                ->havingRaw('SUM(score) BETWEEN ? AND ?', [11, 17]);
        })->count();
        $countDanger = CommercialUnit::query()->whereHas('scores', function ($query) {
            $query->select(\Illuminate\Support\Facades\DB::raw('commercial_unit_id'))
                ->groupBy('commercial_unit_id')
                ->havingRaw('SUM(score) BETWEEN ? AND ?', [0, 10]);
        })->count();
        return [
            Stat::make('Cumplen: 18-20', $countSuccess)
                ->description($total ? number_format($countSuccess / $total * 100) : 0 . '%')
                ->descriptionIcon('heroicon-c-bolt')
                ->color('success'),
            Stat::make('Fase de Mejora: 11-17', $countWarning)
                ->description($total ? number_format($countWarning / $total * 100) : 0 . '%')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('warning'),
            Stat::make('Zona Roja: 0-10', $countDanger)
                ->description($total ? number_format($countDanger / $total * 100) : 0 . '%')
                ->descriptionIcon('heroicon-s-trash')
                ->color('danger'),
        ];
    }
}
