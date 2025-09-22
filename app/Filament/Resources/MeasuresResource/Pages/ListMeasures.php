<?php

namespace App\Filament\Resources\MeasuresResource\Pages;

use App\Filament\Resources\MeasuresResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListMeasures extends ListRecords
{
    protected static string $resource = MeasuresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
           // PrintAction::make(),
        ];
    }
}
