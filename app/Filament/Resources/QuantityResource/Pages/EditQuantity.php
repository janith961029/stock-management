<?php

namespace App\Filament\Resources\QuantityResource\Pages;

use App\Filament\Resources\QuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuantity extends EditRecord
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
           

        ];
    }
}
