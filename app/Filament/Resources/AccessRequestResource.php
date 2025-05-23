<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessRequestResource\Pages;
use App\Filament\Resources\AccessRequestResource\RelationManagers;
use App\Models\AccessRequest;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessRequestResource extends Resource
{
    protected static ?string $model = AccessRequest::class;

    protected static ?string $navigationGroup = 'ASA';

    protected static ?string $navigationLabel = 'Solicitudes';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $modelLabel = 'Solicitud';
    protected static ?string $pluralModelLabel = 'Solicitudes';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('request_type_id')
                            ->label('Tipo Solicitud')
                            ->relationship(name: 'requestType', titleAttribute: 'name')
                            ->required(),
                        Forms\Components\Select::make('activity_id')
                            ->label('Actividad')
                            ->relationship(name: 'activity', titleAttribute: 'name')
                            ->preload()
                            ->optionsLimit(20)
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('status')
                            ->label('Estado')
                            ->default('Ingresada')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('commercial_unit_id')
                            ->label('Local')
                            ->relationship(name: 'commercialUnit', titleAttribute: 'name')
                            ->preload()
                            ->optionsLimit(20)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('provider_id')
                            ->label('Proveedor')
                            ->relationship(name: 'provider', titleAttribute: 'commercial_name')
                            ->optionsLimit(20)
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id)
                            ->required(),
                        Forms\Components\DatePicker::make('request_date')
                            ->label('Fecha Solicitud')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('applicant')
                            ->label('Solicitante')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('responsible')
                            ->label('Responsable')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('responsible_ci')
                            ->label('Cédula Responsable')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('responsible_phone')
                            ->label('Teléfono Responsable')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('details')
                            ->label('Detalle')
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('requestType.name')
                    ->label('Tipo Solicitud')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activity.name')
                    ->label('Actividad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commercialUnit.name')
                    ->label('Local')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Ingreso')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Fecha Solicitud')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applicant')
                    ->label('Solicitante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible')
                    ->label('Responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible_ci')
                    ->label('Cédula Responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible_phone')
                    ->label('Teléfono Responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provider.commercial_name')
                    ->label('Proveedor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('id')
                    ->form([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['id'] ?? null,
                                fn(Builder $query, $id): Builder => $query->where('id', $id),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['id']) {
                            return null;
                        }
                        return 'Solicitud #:  ' . $data['id'];
                    }),
                Tables\Filters\SelectFilter::make('requestType')
                    ->relationship('requestType', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('activity')
                    ->relationship('activity', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Draft',
                        'reviewing' => 'Reviewing',
                        'published' => 'Published',
                    ])
                    ->attribute('status_id'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('F. Ingreso Desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('F. Ingreso Hasta'),
                    ])->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Desde ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Hasta ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('commercialUnit')
                    ->relationship('commercialUnit', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(4)
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
            RelationManagers\ArtifactsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccessRequests::route('/'),
            'create' => Pages\CreateAccessRequest::route('/create'),
            'edit' => Pages\EditAccessRequest::route('/{record}/edit'),
        ];
    }
}
