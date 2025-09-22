<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIssueItem extends CreateRecord
{
    protected static string $resource = IssueItemResource::class;
     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Person')
        ->body('The Person Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-user-plus');
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
