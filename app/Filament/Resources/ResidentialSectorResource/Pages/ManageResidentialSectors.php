<?php

namespace App\Filament\Resources\ResidentialSectorResource\Pages;

use App\Filament\Resources\ResidentialSectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageResidentialSectors extends ManageRecords
{
    protected static string $resource = ResidentialSectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->modalWidth(MaxWidth::Medium),
        ];
    }
}
