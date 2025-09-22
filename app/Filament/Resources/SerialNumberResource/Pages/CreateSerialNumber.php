<?php

namespace App\Filament\Resources\SerialNumberResource\Pages;

use App\Filament\Resources\SerialNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSerialNumber extends CreateRecord
{
    protected static string $resource = SerialNumberResource::class;
        protected function getRedirectUrl(): string
{
    // Resource එකේ index page එකට redirect වෙනවා
    return $this->getResource()::getUrl('index');
}
}
