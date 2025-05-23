<?php

namespace App\Filament\Resources\AccessRequestResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtifactsRelationManager extends RelationManager
{
    protected static string $relationship = 'artifacts';

    protected static ?string $modelLabel = 'objeto';
    protected static ?string $pluralModelLabel = 'objetos';
    protected static ?string $title = 'Objetos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('type')
                    ->label('Tipo Movimiento')
                    ->inline()
                    ->grouped()
                    ->options([
                        'Ingreso' => 'Ingreso',
                        'Salida' => 'Salida'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('location')
                    ->label('Ubicación'),
                Forms\Components\TextInput::make('description')
                    ->label('Observaciones'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Objeto'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo Movimiento'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ubicación'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Observaciones'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelectSearchColumns(['code', 'name'])
                    ->recordTitleAttribute('name')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->where('artifacts.status', '=', 'Activo'))
                    ->modalWidth(MaxWidth::FourExtraLarge)
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                $action->getRecordSelect()
                                    ->label('Objeto')
                                    ->hiddenLabel(false)
                                    ->required(),
                                Forms\Components\ToggleButtons::make('type')
                                    ->label('Tipo Movimiento')
                                    ->inline()
                                    ->grouped()
                                    ->options([
                                        'Ingreso' => 'Ingreso',
                                        'Salida' => 'Salida'
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('location')
                                    ->label('Ubicación'),
                                Forms\Components\TextInput::make('description')
                                    ->label('Observaciones')
                                    ->columnSpanFull(),
                            ])
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DetachAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
