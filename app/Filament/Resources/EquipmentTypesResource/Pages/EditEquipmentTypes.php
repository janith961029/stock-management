<?php

namespace App\Filament\Resources\EquipmentTypesResource\Pages;

use App\Filament\Resources\EquipmentTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentTypes extends EditRecord
{
    protected static string $resource = EquipmentTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
