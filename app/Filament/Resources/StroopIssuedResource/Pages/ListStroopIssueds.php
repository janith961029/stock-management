<?php

namespace App\Filament\Resources\StroopIssuedResource\Pages;

use App\Filament\Resources\StroopIssuedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use Illuminate\Database\Eloquent\Builder; // ✅ Add this import

class ListStroopIssueds extends ListRecords
{
    protected static string $resource = StroopIssuedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            // PrintAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where(function ($query) {
                $query->where('confirmedstatus', 'confirmed')
                      ->orWhere('issuedstatus', 'issued');
            });
    }
}
