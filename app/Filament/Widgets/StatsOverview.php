<?php

namespace App\Filament\Widgets;

use App\Models\CommercialUnit;
use App\Models\Contract;
use App\Models\Provider;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', User::query()->count())
                ->description('Total')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('primary'),
            Stat::make('Contratos', Contract::query()->count())
                ->description('Total')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('primary'),
            Stat::make('Locales Comerciales', CommercialUnit::query()->count())
                ->description('Total')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('primary'),
            Stat::make('Proveedores', Provider::query()->count())
                ->description('Total')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('primary'),
        ];
    }
}
