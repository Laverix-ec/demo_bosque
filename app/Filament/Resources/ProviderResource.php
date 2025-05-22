<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProviderResource\Pages;
use App\Filament\Resources\ProviderResource\RelationManagers;
use App\Models\Provider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    protected static ?string $navigationGroup = 'Parametrización';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Proveedores';
    protected static ?string $recordTitleAttribute = 'ruc';
    protected static ?string $modelLabel = 'proveedor';
    protected static ?string $pluralModelLabel = 'proveedores';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('commercial_name')
                    ->label('Nombre Comercial')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ruc')
                    ->required()
                    ->maxLength(13),
                Forms\Components\TextInput::make('legal_name')
                    ->label('Razón Social')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_name')
                    ->label('Nombre Contacto')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_email')
                    ->label('Correo Contacto')
                    ->email()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commercial_name')
                    ->label('Nombre Comercial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Razón Social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Nombre Contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Correo Contacto')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
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
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }
}
