<?php

namespace App\Filament\Resources\StroopRequestResource\Pages;

use App\Filament\Resources\StroopRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use Illuminate\Database\Eloquent\Builder; // ✅ correct import

class ListStroopRequests extends ListRecords
{
    protected static string $resource = StroopRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
           
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('requestedstatus', 'requested')
            ->where(function ($query) {
                $query->whereNull('confirmedstatus')
                      ->orWhere('confirmedstatus', '');
            });
    }
}
