<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Imports\ContractsImport;
use App\Models\Contract;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationGroup = 'Contratos';
    protected static ?string $navigationLabel = 'Contratos';
    # protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'contract_number';
    protected static ?string $modelLabel = 'contrato';
    protected static ?string $pluralModelLabel = 'contratos';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('contract_number')
                                    ->label('Número Contrato')
                                    ->autofocus()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contract_object')
                                    ->label('Objeto del Contrato')
                                    ->required()
                                    ->maxLength(255)
                            ]),
                        Forms\Components\Tabs\Tab::make('Gestión')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('internal_admin_id')
                                    ->label('Administrador Interno')
                                    ->relationship(name: 'admin', titleAttribute: 'name')
                                    ->required(),
                                Forms\Components\Select::make('department_id')
                                    ->label('Departamento')
                                    ->relationship(name: 'department', titleAttribute: 'name')
                                    ->required(),
                                Forms\Components\Select::make('provider_id')
                                    ->label('Proveedor')
                                    ->relationship(name: 'provider', titleAttribute: 'commercial_name')
                                    ->createOptionForm([
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
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('product_service')
                                    ->label('Producto/Servicio')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Tabs\Tab::make('Términos')
                            ->columns(2)
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Fecha Inicio'),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Fecha Fin'),
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'VIGENTE' => 'VIGENTE',
                                        'EN PROCESO' => 'EN PROCESO',
                                        'FINALIZADO' => 'FINALIZADO',
                                    ]),
                                Forms\Components\TextInput::make('addenda')
                                    ->label('Adenda')
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('auto_renewal')
                                    ->label('Renovación Automática'),
                                Forms\Components\Textarea::make('observation')
                                    ->label('Observaciones')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Datos Financieros')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('account_code')
                                    ->label('Código Cuenta')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contract_cost')
                                    ->label('Costo del Contrato')
                                    ->numeric()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $old, $state) {
                                        $set('total_cost', $state + $get('approved_additional_costs'));
                                    }),
                                Forms\Components\TextInput::make('approved_additional_costs')
                                    ->label('Costos Adicionales')
                                    ->numeric()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $old, $state) {
                                        $set('total_cost', floatval($state) + floatval($get('contract_cost')));
                                    }),
                                Forms\Components\TextInput::make('approved_budget')
                                    ->label('Presupuesto Aprobado')
                                    ->numeric()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $old, $state) {
                                        $set('cost_vs_budget', $state - $get('total_cost'));
                                    }),
                                Forms\Components\TextInput::make('total_cost')
                                    ->label('Costo Total')
                                    ->readOnly(true)
                                    ->numeric(),
                                Forms\Components\TextInput::make('cost_vs_budget')
                                    ->label('Costo vs Presupuesto')
                                    ->readOnly(true)
                                    ->numeric(),
                                Forms\Components\Textarea::make('payment_terms')
                                    ->label('Términos de Pagos')
                                    ->columnSpanFull(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Número Contrato')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Responsable')
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider.commercial_name')
                    ->label('Proveedor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha Inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha Fin')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VIGENTE' => 'success',
                        'EN PROCESO' => 'warning',
                        'FINALIZADO' => 'danger'
                    }),
                Tables\Columns\TextColumn::make('contract_cost')
                    ->label('Costo')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_additional_costs')
                    ->label('Costos Adicionales')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_budget')
                    ->label('Presupuesto Aprobado')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('USD', divideBy: 100)->label('Total'))
                    ->label('Costo Total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_vs_budget')
                    ->label('Costo vs Presupuesto')
                    ->money('USD')
                    ->sortable()
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
            RelationManagers\DeliverablesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
