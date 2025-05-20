<?php

namespace App\Filament\Widgets;

use App\Models\Contract;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactStatsOverview extends BaseWidget
{

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $budget = Contract::query()->sum('approved_budget');
        $totalCost = Contract::query()->sum('total_cost');
        $percent = $totalCost ? $totalCost / $budget : 0;
        $description = '';
        $descriptionIcon = '';
        $color = '';
        if ($percent > 1) {
            $description = 'Déficit ' . number_format(($percent - 1) * 100) . '%';
            $descriptionIcon = 'heroicon-m-arrow-trending-down';
            $color = 'danger';
        } elseif ($percent < 1) {
            $description = number_format((1 - $percent) * 100) . '% Por Contratar';
            $descriptionIcon = 'heroicon-m-arrow-trending-up';
            $color = 'success';
        }
        return [
            Stat::make('Presupuesto Contratos', '$' . number_format($budget / 100, 2))
                ->description('Total Aprobado')
                ->descriptionIcon('heroicon-m-document-currency-dollar')
                ->color('success'),
            Stat::make('Valor Contratos', '$' . number_format($totalCost / 100, 2))
                ->description($description)
                ->descriptionIcon($descriptionIcon)
                ->color($color),
        ];
    }
}
