<?php

namespace App\Filament\Resources\EquipmentTypesResource\Pages;

use App\Filament\Resources\EquipmentTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListEquipmentTypes extends ListRecords
{
    protected static string $resource = EquipmentTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //PrintAction::make(),
        ];
    }
}
