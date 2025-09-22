<?php

namespace App\Filament\Resources\VotesResource\Pages;

use App\Filament\Resources\VotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVotes extends ListRecords
{
    protected static string $resource = VotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
