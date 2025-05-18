<?php

namespace App\Filament\Resources\CommercialUnitResource\Pages;

use App\Filament\Resources\CommercialUnitResource;
use App\Imports\CommercialUnitsImport;
use App\Models\CommercialUnit;
use Filament\Actions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListCommercialUnits extends ListRecords
{
    protected static string $resource = CommercialUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Importar')
                ->color('gray')
                ->form([
                    SpatieMediaLibraryFileUpload::make('file')
                        ->required()
                        ->afterStateUpdated(function (callable $set, TemporaryUploadedFile $state) {
                            $set('fileRealPath', $state->getRealPath());
                        }),
                    Hidden::make('fileRealPath'),
                ])
                ->action(function ($data) {
                    (new CommercialUnitsImport())->import($data['fileRealPath']);
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CommercialUnitResource\Widgets\CommercialUnitStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('TODOS')
                ->badge(CommercialUnit::query()->count()),
            'zone_1' => Tab::make('ZONA 1')
                ->badge(CommercialUnit::query()->where('zone', 'ZONA 1')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('zone', 'ZONA 1')),
            'zone_2' => Tab::make('ZONA 2')
                ->badge(CommercialUnit::query()->where('zone', 'ZONA 2')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('zone', 'ZONA 2')),
            'zone_3' => Tab::make('ZONA 3')
                ->badge(CommercialUnit::query()->where('zone', 'ZONA 3')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('zone', 'ZONA 3')),
            'zone_4' => Tab::make('ZONA 4')
                ->badge(CommercialUnit::query()->where('zone', 'ZONA 4')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('zone', 'ZONA 4')),
            'zone_5' => Tab::make('ZONA 5')
                ->badge(CommercialUnit::query()->where('zone', 'ZONA 5')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('zone', 'ZONA 5')),
        ];
    }
}
