<?php

namespace App\Filament\Resources\SignalUnitResource\Pages;

use App\Filament\Resources\SignalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListSignalUnits extends ListRecords
{
    protected static string $resource = SignalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            PrintAction::make(),
        ];
    }
}
