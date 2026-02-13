<?php

namespace App\Filament\Resources\IssuingTypeResource\Pages;

use App\Filament\Resources\IssuingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListIssuingTypes extends ListRecords
{
    protected static string $resource = IssuingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //PrintAction::make(),
        ];
    }
}
