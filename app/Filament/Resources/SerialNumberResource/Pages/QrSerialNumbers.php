<?php

namespace App\Filament\Resources\SerialNumberResource\Pages;

use App\Filament\Resources\SerialNumberResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class QrSerialNumbers extends Page
{
    protected static string $resource = SerialNumberResource::class;

    protected static string $view = 'filament.resources.serial-number-resource.pages.qr-serial-numbers';

    use InteractsWithRecord;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
