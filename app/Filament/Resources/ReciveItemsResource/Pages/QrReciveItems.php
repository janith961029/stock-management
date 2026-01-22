<?php

namespace App\Filament\Resources\ReciveItemsResource\Pages;

use App\Filament\Resources\ReciveItemsResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class QrReciveItems extends Page
{
    protected static string $resource = ReciveItemsResource::class;

    protected static string $view = 'filament.resources.issue-item-resource.pages.qr-issue-items';

    use InteractsWithRecord;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
