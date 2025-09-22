<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Role')
        ->body('The Role Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-shield-check');
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
