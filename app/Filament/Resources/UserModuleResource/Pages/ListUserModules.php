<?php

namespace App\Filament\Resources\UserModuleResource\Pages;

use App\Filament\Resources\UserModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListUserModules extends ListRecords
{
    protected static string $resource = UserModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
           // PrintAction::make(),
        ];
    }
}
