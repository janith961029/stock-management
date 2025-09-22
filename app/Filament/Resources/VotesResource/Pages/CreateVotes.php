<?php

namespace App\Filament\Resources\VotesResource\Pages;

use App\Filament\Resources\VotesResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVotes extends CreateRecord
{
    protected static string $resource = VotesResource::class;
               protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Votes')
        ->body('The Votes Added Successfully')
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
