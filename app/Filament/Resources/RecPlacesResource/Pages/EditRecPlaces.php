<?php

namespace App\Filament\Resources\RecPlacesResource\Pages;

use App\Filament\Resources\RecPlacesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecPlaces extends EditRecord
{
    protected static string $resource = RecPlacesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
