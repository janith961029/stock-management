<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
        protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('User')
        ->body('The User Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-users');
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

