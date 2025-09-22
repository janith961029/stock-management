<?php

namespace App\Filament\Resources\ItemResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewItem extends BaseWidget
{
    protected function getStats(): array
    {
        return [
          ItemResource\Widgets\Newitem::class,
        ];
    }
}
