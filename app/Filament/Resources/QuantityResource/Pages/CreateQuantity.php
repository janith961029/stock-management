<?php

namespace App\Filament\Resources\QuantityResource\Pages;

use App\Filament\Resources\QuantityResource;
use Filament\Actions;
use Filament\Notifications\Notification;

use Filament\Resources\Pages\CreateRecord;

class CreateQuantity extends CreateRecord
{
    protected static string $resource = QuantityResource::class;
        protected function getRedirectUrl(): string
 

{
    




    return $this->getResource()::getUrl('index');
}



protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Module')
        ->body('The Old Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-plus');
    }
    public function successRedirectUrl()
    {
        return route('resource.create');
    }
}
