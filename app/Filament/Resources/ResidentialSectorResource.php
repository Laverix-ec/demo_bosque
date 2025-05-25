<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentialSectorResource\Pages;
use App\Filament\Resources\ResidentialSectorResource\RelationManagers;
use App\Models\ResidentialSector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class ResidentialSectorResource extends Resource
{
    protected static ?string $model = ResidentialSector::class;

    protected static ?string $navigationGroup = 'Parametrización';
    protected static ?int $navigationSort = 35;
    protected static ?string $navigationLabel = 'Sectores';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Sector';
    protected static ?string $pluralModelLabel = 'Sectores';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Radio::make('status')
                    ->label('Estado')
                    ->default('Activo')
                    ->inline()
                    ->inlineLabel(false)
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Activo' => 'success',
                        'Inactivo' => 'danger'
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->modalWidth(MaxWidth::Medium),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageResidentialSectors::route('/'),
        ];
    }
}
