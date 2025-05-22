<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommercialCategoryResource\Pages;
use App\Filament\Resources\CommercialCategoryResource\RelationManagers;
use App\Models\CommercialCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommercialCategoryResource extends Resource
{
    protected static ?string $model = CommercialCategory::class;

    protected static ?string $navigationGroup = 'Parametrización';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Categorías Comerciales';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'categoría';
    protected static ?string $pluralModelLabel = 'Categorías Comerciales';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCommercialCategories::route('/')
        ];
    }
}
