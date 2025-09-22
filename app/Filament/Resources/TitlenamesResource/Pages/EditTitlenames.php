<?php

namespace App\Filament\Resources\TitlenamesResource\Pages;

use App\Filament\Resources\TitlenamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTitlenames extends EditRecord
{
    protected static string $resource = TitlenamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
