<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
       protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Permission')
        ->body('The Permission Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-shield-exclamation');
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
