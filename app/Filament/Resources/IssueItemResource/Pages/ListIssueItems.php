<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssueItems extends ListRecords
{
    protected static string $resource = IssueItemResource::class;

    //protected function getHeaderActions(): array
    //{
       // return [
       //     Actions\CreateAction::make(),
      //  ];
   // }
}
