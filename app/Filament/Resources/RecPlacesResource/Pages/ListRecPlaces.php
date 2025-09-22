<?php

namespace App\Filament\Resources\RecPlacesResource\Pages;

use App\Filament\Resources\RecPlacesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListRecPlaces extends ListRecords
{
    protected static string $resource = RecPlacesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            PrintAction::make(),
        ];
    }
}
