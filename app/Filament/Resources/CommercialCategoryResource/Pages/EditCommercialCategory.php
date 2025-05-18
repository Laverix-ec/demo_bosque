<?php

namespace App\Filament\Resources\CommercialCategoryResource\Pages;

use App\Filament\Resources\CommercialCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommercialCategory extends EditRecord
{
    protected static string $resource = CommercialCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
