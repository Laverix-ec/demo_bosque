<?php

namespace App\Filament\Pages;

use App\Models\AccessRequest;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivitySupervision extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationGroup = 'ASA';

    protected static ?string $navigationLabel = 'Supervisión Actividades';

    protected ?string $heading = 'Supervisión Actividades';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.activity-supervision';

    public static function table(Table $table): Table
    {
        return $table
            ->query(AccessRequest::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activity.name')
                    ->label('Actividad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('commercialUnit.name')
                    ->label('Local')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_done')
                ->label('Realizada'),
                TextColumn::make('created_at')
                    ->label('Fecha Ingreso')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('request_date')
                    ->label('Fecha Solicitud')
                    ->date()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Estado')
                    ->options([
                        'Ingresada' => 'Ingresada',
                        'Aprobada' => 'Aprobada',
                        'Cancelada' => 'Cancelada',
                    ])
                    ->selectablePlaceholder(false),
            ])
            ->filters([
                Filter::make('id')
                    ->form([
                        TextInput::make('id')
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('F. Ingreso Desde'),
                        DatePicker::make('created_until')
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
                SelectFilter::make('commercialUnit')
                    ->relationship('commercialUnit', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
