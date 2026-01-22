<?php

namespace App\Filament\Resources\CsoapprovalResource\Pages;

use App\Filament\Resources\CsoapprovalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use Illuminate\Database\Eloquent\Builder;

class ListCsoapprovals extends ListRecords
{
    protected static string $resource = CsoapprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            // PrintAction::make(),
        ];
    }

    // ✅ must NOT be static
    protected function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->where(function ($query) {
            $query->where('requestedstatus', 'requested')
                  ->orWhere('confirmedstatus', 'confirmed');
        })
        ->where(function ($query) {
            $query->whereNull('issuedstatus')
                  ->orWhere('issuedstatus', '');
        });
}
}
