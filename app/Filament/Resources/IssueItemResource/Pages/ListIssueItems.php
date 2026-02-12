<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListIssueItems extends ListRecords
{
    protected static string $resource = IssueItemResource::class;

    //protected function getHeaderActions(): array
    //{
       // return [
       //     Actions\CreateAction::make(),
      //  ];
   // }

    protected function applyGlobalSearchToTableQuery(Builder $query): Builder
    {
        $search = $this->getTableSearch();

        if (blank($search)) {
            return $query;
        }

        foreach ($this->extractTableSearchWords($search) as $searchWord) {
            $query->where(function (Builder $query) use ($searchWord) {
                $isFirst = true;

                foreach ($this->getTable()->getColumns() as $column) {
                    if (! $column->isGloballySearchable()) {
                        continue;
                    }

                    $column->applySearchConstraint(
                        $query,
                        $searchWord,
                        $isFirst,
                    );
                }
            });
        }

        return $query;
    }
}
