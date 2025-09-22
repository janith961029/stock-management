<?php

namespace App\Filament\Resources\QuantityResource\Pages;

use App\Filament\Resources\QuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListQuantities extends ListRecords
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Actions\CreateAction::make(),
           // PrintAction::make(),
        ];
    }
}
