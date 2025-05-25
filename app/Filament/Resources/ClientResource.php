<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Gestión Comercial';

    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Local')
                            ->columns(3)
                            ->schema([
                                Forms\Components\ToggleButtons::make('identification_type')
                                    ->label('Tipo Identificación')
                                    ->inline()
                                    ->grouped()
                                    ->options([
                                        'Cédula' => 'Cédula',
                                        'Ruc' => 'Ruc'
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('identification')
                                    ->label('Identificación')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Radio::make('status')
                                    ->label('Estado')
                                    ->default('Activo')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->options([
                                        'Activo' => 'Activo',
                                        'Inactivo' => 'Inactivo'
                                    ]),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('first_last_name')
                                    ->label('Primer Apellido')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('second_last_name')
                                    ->label('Segundo Apellido')
                                    ->maxLength(255),
                                Forms\Components\Select::make('residential_sector_id')
                                    ->label('Sector Residencia')
                                    ->relationship(name: 'residentialSector', titleAttribute: 'name')
                                    ->preload()
                                    ->optionsLimit(20)
                                    ->searchable()
                                    ->required(),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Fecha Nacimiento'),
                                Forms\Components\Textarea::make('address')
                                    ->label('Dirección')
                                    ->columnSpanFull()
                            ]),
                        Forms\Components\Tabs\Tab::make('Contactos')
                            ->schema([
                                Forms\Components\Repeater::make('contacts')
                                    ->label('Contactos')
                                    ->relationship()
                                    ->defaultItems(0)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->label('Tipo')
                                            ->options([
                                                'MOVIL' => 'MOVIL',
                                                'HOGAR' => 'HOGAR',
                                                'EMAIL' => 'EMAIL',
                                            ])
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->required(),
                                        Forms\Components\TextInput::make('value')
                                            ->label('Valor')
                                            ->live(onBlur: true)
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn(array $state): ?string => $state['value'] ?? null),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification')
                    ->label('Identificación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('residentialSector.name')
                    ->label('Sector')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Activo' => 'success',
                        'Inactivo' => 'danger'
                    })
                    ->sortable()
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
