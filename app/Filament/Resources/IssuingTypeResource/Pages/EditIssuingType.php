<?php

namespace App\Filament\Resources\IssuingTypeResource\Pages;

use App\Filament\Resources\IssuingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIssuingType extends EditRecord
{
    protected static string $resource = IssuingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
