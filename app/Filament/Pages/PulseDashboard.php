<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Closure;
use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Support\Enums\ActionSize;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class PulseDashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;

    protected static ?string $title = 'Métricas del Servidor';

    protected ?string $heading = 'Métricas del Servidor';
    protected static  ?string $navigationLabel = 'Métricas';
    protected static ?string $navigationGroup = 'Sistema';
    protected static string $routePath = 'system-metrics';

    protected static ?string $navigationIcon = '';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public function getColumns(): int|string|array
    {
        return 12;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('1h')
                    ->action(fn() => $this->redirect(self::getUrl())),
                Action::make('24h')
                    ->action(fn() => $this->redirect(self::getUrl(['period' => '24_hours']))),
                Action::make('7d')
                    ->action(fn() => $this->redirect(self::getUrl(['period' => '7_days']))),
            ])
                ->label(__('Filtros'))
                ->icon('heroicon-m-funnel')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
        ];
    }

    public function getWidgets(): array
    {
        return [
            PulseServers::class,
            PulseUsage::class,
            PulseQueues::class,
            PulseCache::class,
            PulseSlowRequests::class,
            PulseSlowQueries::class,
        ];
    }
}
