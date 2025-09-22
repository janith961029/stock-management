<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
class QrIssueItems extends Page
{
    protected static string $resource = IssueItemResource::class;

    protected static string $view = 'filament.resources.issue-item-resource.pages.qr-issue-items';
 use InteractsWithRecord;

     public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }



}
