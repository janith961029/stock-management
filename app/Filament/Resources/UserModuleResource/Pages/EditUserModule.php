<?php

namespace App\Filament\Resources\UserModuleResource\Pages;

use App\Filament\Resources\UserModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserModule extends EditRecord
{
    protected static string $resource = UserModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
