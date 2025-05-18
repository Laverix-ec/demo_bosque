<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Imports\ContractsImport;
use App\Models\Contract;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ContractResource\Widgets\ContractStats::class,
        ];
    }

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
                    (new ContractsImport)->import($data['fileRealPath']);
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('TODOS')
                ->badge(Contract::query()->count()),
            'active' => Tab::make('VIGENTES')
                ->badge(Contract::query()->where('status', 'VIGENTE')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'VIGENTE')),
            'in_progress' => Tab::make('EN PROCESO')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'EN PROCESO'))
                ->badge(Contract::query()->where('status', 'EN PROCESO')->count())
                ->badgeColor('warning'),
            'finished' => Tab::make('FINALIZADOS')
                ->badge(Contract::query()->where('status', 'FINALIZADO')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'FINALIZADO')),
        ];
    }
}
