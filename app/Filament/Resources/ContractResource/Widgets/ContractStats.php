<?php

namespace App\Filament\Resources\ContractResource\Widgets;

use App\Casts\MoneyCast;
use App\Filament\Resources\ContractResource\Pages\ListContracts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContractStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    public array $tableColumnSearches = [];

    protected function getTablePage(): string
    {
        return ListContracts::class;
    }

    protected function getStats(): array
    {
        $budget = $this->getPageTableQuery()->sum('approved_budget');
        $totalCost = $this->getPageTableQuery()->sum('total_cost');
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
            Stat::make('Total', $this->getPageTableQuery()->count())
                ->description('Contratos')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('info'),
            Stat::make('Presupuesto', '$' . number_format($budget / 100, 2))
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
