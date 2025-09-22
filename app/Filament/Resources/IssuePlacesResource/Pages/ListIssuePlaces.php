<?php

namespace App\Filament\Resources\IssuePlacesResource\Pages;

use App\Filament\Resources\IssuePlacesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListIssuePlaces extends ListRecords
{
    protected static string $resource = IssuePlacesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //PrintAction::make(),
        ];
    }
}
