<?php

namespace App\Filament\Widgets;

use App\Models\Items;
use App\Models\Store;
use App\Models\IctCategories;
use App\Models\EquipmentTypes;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class Item extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Items', Items::count())
                ->description('All items in Stock')
                ->color('success')
                ->chart([7,2,10,3,15,4,17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Total Stores', Store::count())
                ->description('All Stores')
                ->color('danger')
                ->chart([7,2,10,3,15,4,17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Total ICT Categories', IctCategories::count())
                ->description('All ICT Categories')
                ->color('info')
                ->chart([7,2,10,3,15,4,17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Total Equipment Type', EquipmentTypes::count())
                ->description('All Equipment Type')
                ->color('warning')
                ->chart([7,2,10,3,15,4,17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }
}
