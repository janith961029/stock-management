<?php

namespace App\Filament\Resources\IctCategoriesResource\Pages;

use App\Filament\Resources\IctCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListIctCategories extends ListRecords
{
    protected static string $resource = IctCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //PrintAction::make(),
        ];
    }
}
