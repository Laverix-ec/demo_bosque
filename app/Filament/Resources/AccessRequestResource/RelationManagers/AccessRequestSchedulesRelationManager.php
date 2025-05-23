<?php

namespace App\Filament\Resources\AccessRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessRequestSchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'accessRequestSchedules';

    protected static ?string $modelLabel = 'horario';
    protected static ?string $pluralModelLabel = 'horarios';
    protected static ?string $title = 'Horarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->label('Fecha Desde')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Fecha Hasta')
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Hora Desde')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Hora Hasta')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TextInput::make('number_persons')
                    ->label('#. Personas')
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->label('Ejecutores')

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha Desde')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha Hasta')
                    ->date(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora Desde')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora Hasta')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('number_persons')
                    ->label('#. Personas'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Ejecutores'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
