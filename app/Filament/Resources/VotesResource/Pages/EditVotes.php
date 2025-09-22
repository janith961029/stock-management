<?php

namespace App\Filament\Resources\VotesResource\Pages;

use App\Filament\Resources\VotesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVotes extends EditRecord
{
    protected static string $resource = VotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
