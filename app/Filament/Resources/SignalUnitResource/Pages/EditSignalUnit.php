<?php

namespace App\Filament\Resources\SignalUnitResource\Pages;

use App\Filament\Resources\SignalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSignalUnit extends EditRecord
{
    protected static string $resource = SignalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
