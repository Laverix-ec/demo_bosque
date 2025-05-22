<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestrictionResource\Pages;
use App\Filament\Resources\RestrictionResource\RelationManagers;
use App\Models\Restriction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestrictionResource extends Resource
{
    protected static ?string $model = Restriction::class;

    protected static ?string $navigationGroup = 'Parametrización';

    protected static ?string $navigationLabel = 'Restricciones';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $modelLabel = 'Restricción';
    protected static ?string $pluralModelLabel = 'Restricciones';
    protected static ?int $navigationSort = 30;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'General' => 'General',
                        'Particular' => 'Particular'
                    ])
                    ->default('General')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo'
                    ])
                    ->default('Activo')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->description(fn (Restriction $record): string => $record->type)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Activo' => 'success',
                        'Inactivo' => 'danger'
                    })
                    ->searchable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRestrictions::route('/'),
        ];
    }
}
