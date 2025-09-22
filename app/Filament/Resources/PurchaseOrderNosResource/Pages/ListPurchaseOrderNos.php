<?php

namespace App\Filament\Resources\PurchaseOrderNosResource\Pages;

use App\Filament\Resources\PurchaseOrderNosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrderNos extends ListRecords
{
    protected static string $resource = PurchaseOrderNosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
