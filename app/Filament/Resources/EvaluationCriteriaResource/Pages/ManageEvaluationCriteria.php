<?php

namespace App\Filament\Resources\EvaluationCriteriaResource\Pages;

use App\Filament\Resources\EvaluationCriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEvaluationCriteria extends ManageRecords
{
    protected static string $resource = EvaluationCriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
