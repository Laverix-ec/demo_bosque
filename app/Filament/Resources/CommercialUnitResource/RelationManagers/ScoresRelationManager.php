<?php

namespace App\Filament\Resources\CommercialUnitResource\RelationManagers;

use App\Models\EvaluationCriteria;
use App\Models\UnitScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'scores';

    protected static ?string $modelLabel = 'calificación';
    protected static ?string $pluralModelLabel = 'calificaciones';
    protected static ?string $title = 'Calificaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('evaluation_criteria_id')
                    ->label('Variable')
                    ->live(onBlur: true)
                    ->relationship(
                        name: 'criteria',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->whereNotIn('id', UnitScore::query()
                                ->where('commercial_unit_id', $this->getOwnerRecord()->getKey())
                                ->select('evaluation_criteria_id')),
                    )
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('score')
                    ->label('Calificación')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->maxValue(fn (Forms\Get $get): int => filled($get('evaluation_criteria_id')) ? EvaluationCriteria::query()->find($get('evaluation_criteria_id'))->max_score:0 ),
                Forms\Components\DatePicker::make('evaluation_date')
                    ->label('Fecha'),
                Forms\Components\Textarea::make('comment')
                    ->label('Comentario'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('criteria.name')
                    ->label('Variable')
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Calificación')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total')),
                Tables\Columns\TextColumn::make('comment')->label('Comentario'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
