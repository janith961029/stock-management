<?php

namespace App\Filament\Resources\IctCategoriesResource\Pages;

use App\Filament\Resources\IctCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIctCategories extends EditRecord
{
    protected static string $resource = IctCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
