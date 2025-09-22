<?php

namespace App\Filament\Resources\IssuePlacesResource\Pages;

use App\Filament\Resources\IssuePlacesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIssuePlaces extends EditRecord
{
    protected static string $resource = IssuePlacesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
