<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluationCriteriaResource\Pages;
use App\Filament\Resources\EvaluationCriteriaResource\RelationManagers;
use App\Models\EvaluationCriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EvaluationCriteriaResource extends Resource
{
    protected static ?string $model = EvaluationCriteria::class;

    protected static ?string $navigationGroup = 'Parametrización';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Variables Evaluación';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'variable';
    protected static ?string $pluralModelLabel = 'variables';

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
                Forms\Components\TextInput::make('max_score')
                    ->label('Puntaje')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_score')
                    ->label('Puntaje')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total'))
                    ->numeric()
                    ->sortable()
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
            'index' => Pages\ManageEvaluationCriteria::route('/')
        ];
    }
}
