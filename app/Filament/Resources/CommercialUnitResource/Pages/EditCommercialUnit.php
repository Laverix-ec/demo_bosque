<?php

namespace App\Filament\Resources\CommercialUnitResource\Pages;

use App\Filament\Resources\CommercialUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommercialUnit extends EditRecord
{
    protected static string $resource = CommercialUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
