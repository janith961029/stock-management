<?php

namespace App\Filament\Resources\TitlenamesResource\Pages;

use App\Filament\Resources\TitlenamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListTitlenames extends ListRecords
{
    protected static string $resource = TitlenamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //PrintAction::make(),
        ];
    }
}
