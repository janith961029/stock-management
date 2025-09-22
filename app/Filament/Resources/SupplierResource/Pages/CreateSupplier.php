<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;
      protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Supplier')
        ->body('The Supplier Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-briefcase');
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
