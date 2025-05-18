<?php

namespace App\Filament\Resources\CommercialCategoryResource\Pages;

use App\Filament\Resources\CommercialCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommercialCategories extends ListRecords
{
    protected static string $resource = CommercialCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
