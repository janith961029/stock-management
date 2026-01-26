<?php

namespace App\Filament\Resources\PurchaseOrderNosResource\Pages;

use App\Filament\Resources\PurchaseOrderNosResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrderNos extends CreateRecord
{
    protected static string $resource = PurchaseOrderNosResource::class;












    
       protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Purchase Order')
        ->body('The Purchase Order Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-rectangle-stack');
    }
    public function successRedirectUrl()
    {
        return route('resource.create');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
