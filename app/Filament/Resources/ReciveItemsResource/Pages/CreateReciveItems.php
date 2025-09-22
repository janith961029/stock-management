<?php

namespace App\Filament\Resources\ReciveItemsResource\Pages;

use App\Filament\Resources\ReciveItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReciveItems extends CreateRecord
{
    protected static string $resource = ReciveItemsResource::class;
        protected function getRedirectUrl(): string
{
    // Resource එකේ index page එකට redirect වෙනවා
    return $this->getResource()::getUrl('index');
}
}
