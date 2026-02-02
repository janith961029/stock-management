<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardInventoryTable;
use App\Filament\Widgets\DashboardOldInventoryTable;
use App\Filament\Widgets\Item;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public bool $showInventoryTable = false;
    public bool $showOldInventoryTable = false;

    public function getWidgets(): array
    {
        $widgets = [
            Item::class,
        ];

        if ($this->showInventoryTable) {
            $widgets[] = DashboardInventoryTable::class;
        }

        if ($this->showOldInventoryTable) {
            $widgets[] = DashboardOldInventoryTable::class;
        }

        return $widgets;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleInventoryTable')
                ->label(fn (): string => $this->showInventoryTable ? 'Hide Item Details' : 'Show Item Details')
                ->icon(fn (): string => $this->showInventoryTable ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                ->color(fn (): string => $this->showInventoryTable ? 'warning' : 'primary')
                ->action(function (): void {
                    $this->showInventoryTable = ! $this->showInventoryTable;
                }),
            Action::make('toggleOldInventoryTable')
                ->label(fn (): string => $this->showOldInventoryTable ? 'Hide Old Item Details' : 'Show Old Item Details')
                ->icon(fn (): string => $this->showOldInventoryTable ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                ->color(fn (): string => $this->showOldInventoryTable ? 'warning' : 'primary')
                ->action(function (): void {
                    $this->showOldInventoryTable = ! $this->showOldInventoryTable;
                }),
        ];
    }
}
