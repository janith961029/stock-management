<?php

namespace App\Filament\Resources\EstablishmentResource\Pages;

use App\Filament\Resources\EstablishmentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEstablishment extends CreateRecord
{
    protected static string $resource = EstablishmentResource::class;
     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Eshtablishment')
        ->body('The Eshtablishment Added Successfully')
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
