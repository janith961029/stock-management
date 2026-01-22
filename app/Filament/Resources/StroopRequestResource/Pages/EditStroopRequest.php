<?php

namespace App\Filament\Resources\StroopRequestResource\Pages;

use App\Filament\Resources\StroopRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStroopRequest extends EditRecord
{
    protected static string $resource = StroopRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
