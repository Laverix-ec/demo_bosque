<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommercialUnitResource\Pages;
use App\Filament\Resources\CommercialUnitResource\RelationManagers;
use App\Filament\Resources\CommercialUnitResource\Widgets\CommercialUnitChart;
use App\Models\CommercialUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommercialUnitResource extends Resource
{
    protected static ?string $model = CommercialUnit::class;

    protected static ?string $navigationGroup = 'Gestión Comercial';

    protected static ?string $navigationLabel = 'Locales Comerciales';

    protected static ?string $recordTitleAttribute = 'local_code';
    protected static ?string $modelLabel = 'Local';
    protected static ?string $pluralModelLabel = 'locales';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Local')
                            ->columns(2)
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->image()
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('4:3')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                                Forms\Components\TextInput::make('local_code')
                                    ->label('Número')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('zone')
                                    ->label('Zona')
                                    ->required()
                                    ->options([
                                        'ZONA 1' => 'ZONA 1',
                                        'ZONA 2' => 'ZONA 2',
                                        'ZONA 3' => 'ZONA 3',
                                        'ZONA 4' => 'ZONA 4',
                                        'ZONA 5' => 'ZONA 5',
                                    ]),
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoría')
                                    ->relationship(name: 'category', titleAttribute: 'name')
                                    ->searchable()
                                    ->preload()
                                    ->optionsLimit(20)
                                    ->required(),
                                Forms\Components\TextInput::make('ruc')
                                    ->label('RUC')
                                    ->required()
                                    ->maxLength(13),
                                Forms\Components\TextInput::make('property_code')
                                    ->label('Predio')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('location')
                                    ->label('Ubicación')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Tabs\Tab::make('CoPropietario')
                            ->schema([
                                Forms\Components\Grid::make([
                                    'default' => 2
                                ])
                                    ->relationship('coTenant')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(255),
                                    ])

                            ]),
                        Forms\Components\Tabs\Tab::make('Locatario')
                            ->schema([
                                Forms\Components\Grid::make([
                                    'default' => 2
                                ])
                                    ->relationship('tenant')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(255),
                                    ])

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
                                    ->itemLabel(fn (array $state): ?string => $state['value'] ?? null),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('zone')
                    ->label('Zona')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('local_code')
                    ->label('Número')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scores_sum_score')
                    ->label('Calificación')
                    ->sum('scores', 'score'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
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
            RelationManagers\ScoresRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommercialUnits::route('/'),
            'create' => Pages\CreateCommercialUnit::route('/create'),
            'edit' => Pages\EditCommercialUnit::route('/{record}/edit'),
            'view' => Pages\ViewCommercialUnit::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Detalles')
                            ->columns(2)
                            ->schema([
                                Fieldset::make('Datos Generales')
                                    ->schema([
                                        TextEntry::make('name')->label('Nombre:')->inlineLabel(true),
                                        TextEntry::make('local_code')->label('Número:')->inlineLabel(true),
                                        TextEntry::make('category.name')->label('Categoría:')->inlineLabel(true),
                                        TextEntry::make('ruc')->label('RUC:')->inlineLabel(true),
                                        TextEntry::make('location')->label('Ubicación:')
                                            ->columnSpanFull()
                                            ->inlineLabel(true),
                                    ]),
                                Fieldset::make('CoPropietario')
                                    ->schema([
                                        TextEntry::make('coTenant.name')->label('Nombre:')->inlineLabel(true),
                                        TextEntry::make('coTenant.email')->label('Correo:'),
                                        TextEntry::make('coTenant.phone')->label('Teléfono:')->inlineLabel(true),
                                    ]),
                                Fieldset::make('Locatario')
                                    ->schema([
                                        TextEntry::make('tenant.name')->label('Nombre:')->inlineLabel(true),
                                        TextEntry::make('tenant.email')->label('Correo:'),
                                        TextEntry::make('tenant.phone')->label('Teléfono:')->inlineLabel(true),
                                    ])
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Livewire::make(CommercialUnitChart::class),
                        Section::make('Imágen')
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('image')->label('')
                                    ->height("18rem")
                            ])

                    ]),
            ]);
    }
}
