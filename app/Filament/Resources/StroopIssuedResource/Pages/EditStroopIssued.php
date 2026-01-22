<?php

namespace App\Filament\Resources\StroopIssuedResource\Pages;

use App\Filament\Resources\StroopIssuedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStroopIssued extends EditRecord
{
    protected static string $resource = StroopIssuedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
