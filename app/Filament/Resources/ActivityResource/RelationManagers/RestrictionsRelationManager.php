<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use App\Models\Restriction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestrictionsRelationManager extends RelationManager
{
    protected static string $relationship = 'restrictions';

    protected static ?string $modelLabel = 'restricción';
    protected static ?string $pluralModelLabel = 'restricciones';
    protected static ?string $title = 'Restricciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo'
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->description(fn (Restriction $record): string => $record->type)
                    ->searchable(),
                Tables\Columns\TextColumn::make('restriction.status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Activo' => 'success',
                        'Inactivo' => 'danger'
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelectSearchColumns(['type', 'description'])
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('restrictions.status', '=', 'Activo'))
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()->required(),
                        Forms\Components\Radio::make('status')
                            ->label('Estado')
                            ->required()
                            ->inline()
                            ->inlineLabel(false)
                            ->options([
                                'Activo' => 'Activo',
                                'Inactivo' => 'Inactivo'
                            ])
                    ])
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
