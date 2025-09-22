<?php

namespace App\Filament\Resources\MeasuresResource\Pages;

use App\Filament\Resources\MeasuresResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeasures extends EditRecord
{
    protected static string $resource = MeasuresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
