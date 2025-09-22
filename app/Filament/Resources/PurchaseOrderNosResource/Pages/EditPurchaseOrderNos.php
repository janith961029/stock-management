<?php

namespace App\Filament\Resources\PurchaseOrderNosResource\Pages;

use App\Filament\Resources\PurchaseOrderNosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrderNos extends EditRecord
{
    protected static string $resource = PurchaseOrderNosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
