<?php

namespace App\Filament\Resources\CsoapprovalResource\Pages;

use App\Filament\Resources\CsoapprovalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCsoapproval extends EditRecord
{
    protected static string $resource = CsoapprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
