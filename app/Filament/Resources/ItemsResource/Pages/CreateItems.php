<?php

namespace App\Filament\Resources\ItemsResource\Pages;

use App\Filament\Resources\ItemsResource;
use App\Models\Items;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateItems extends CreateRecord
{
    protected static string $resource = ItemsResource::class;

   public function mount(): void
    {
        parent::mount();

        $lastId = Items::max('id') ?? 0;
        $nextId = $lastId + 1;

        $this->form->fill([
            'item_code' => 'ITEM-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
        ]);
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Item')
        ->body('The Item Added Successfully')
        ->success()
        ->color('success')
        ->icon('heroicon-o-cube');
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