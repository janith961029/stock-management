<?php

namespace App\Filament\Widgets;

use App\Models\Items;
use App\Models\Serial;
use App\Models\SerialNumbers;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardInventoryTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 10;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Inventory Status')
            ->query($this->getBaseQuery())
            ->defaultSort('serial_numbers.id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title_name')
                    ->label('Title Name')
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('model_name')
                    ->label('Model Name')
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('Barcode')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(static fn (SerialNumbers $record): string => ((int) $record->issued === 1) ? 'Issued' : 'Available')
                    ->color(static fn (string $state): string => $state === 'Issued' ? 'danger' : 'success'),
            ])
            ->headerActions([
                Action::make('printPdf')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn () => $this->getPdfUrl())
                    ->openUrlInNewTab(),
            ])
            ->filters([
                SelectFilter::make('title_name')
                    ->label('Title Name')
                    ->options(fn (): array => $this->getTitleNameOptions())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $this->decodeTitleFilterValue($data['value'] ?? null);

                        if (! $value || ! Schema::hasColumn('items', 'title_names_id')) {
                            return $query;
                        }

                        return $query->where('items.title_names_id', $value);
                    }),
                SelectFilter::make('model_name')
                    ->label('Model Name')
                    ->options(fn (): array => $this->getModelNameOptions())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $this->decodeModelNameFilterValue($data['value'] ?? null);

                        if (! $value || ! Schema::hasColumn('recive_items', 'model_name')) {
                            return $query;
                        }

                        return $query->where('recive_items.model_name', $value);
                    }),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'all' => 'All',
                        'available' => 'Available',
                        'issued' => 'Issued',
                    ])
                    ->default('all')
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'issued') {
                            return $query->where('serial_numbers.issued', 1);
                        }

                        if ($value === 'available') {
                            return $query->where(function (Builder $query): Builder {
                                return $query
                                    ->whereNull('serial_numbers.issued')
                                    ->orWhere('serial_numbers.issued', '!=', 1);
                            });
                        }

                        return $query;
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3);
    }

    protected function getBaseQuery(): Builder
    {
        $serialJoinColumn = $this->resolveSerialJoinColumn();
        $reciveItemsJoinColumn = $this->resolveReciveItemsJoinColumn();

        return SerialNumbers::query()
            ->leftJoin('recive_items', $serialJoinColumn, '=', 'recive_items.id')
            ->leftJoin('items', $reciveItemsJoinColumn, '=', 'items.id')
            ->select([
                'serial_numbers.id',
                'serial_numbers.issued',
                DB::raw($this->convertToUtf8Expression('serial_numbers.serial_number') . ' as serial_number'),
                DB::raw($this->convertToUtf8Expression('serial_numbers.barcode') . ' as barcode'),
                DB::raw($this->convertToUtf8Expression('recive_items.model_name') . ' as model_name'),
                DB::raw($this->convertToUtf8Expression('items.title_names_id') . ' as title_name'),
            ]);
    }

    protected function resolveSerialJoinColumn(): string
    {
        if (Schema::hasColumn('serial_numbers', 'serial_id')) {
            return 'serial_numbers.serial_id';
        }

        if (Schema::hasColumn('serial_numbers', 'recive_items_id')) {
            return 'serial_numbers.recive_items_id';
        }

        return 'serial_numbers.items_id';
    }

    protected function resolveReciveItemsJoinColumn(): string
    {
        if (Schema::hasColumn('recive_items', 'recive_items_id')) {
            return 'recive_items.recive_items_id';
        }

        return 'recive_items.items_id';
    }

    protected function getTitleNameOptions(): array
    {
        $titles = Items::query()
            ->whereNotNull('title_names_id')
            ->where('title_names_id', '!=', '')
            ->distinct()
            ->select(DB::raw($this->convertToUtf8Expression('title_names_id') . ' as title_name'))
            ->orderBy('title_name')
            ->pluck('title_name')
            ->all();

        $options = [];

        foreach ($titles as $title) {
            $key = base64_encode((string) $title);
            $options[$key] = $this->cleanDisplayValue($title, 'N/A');
        }

        return $options;
    }

    protected function getModelNameOptions(): array
    {
        if (! Schema::hasColumn('recive_items', 'model_name')) {
            return [];
        }

        $models = Serial::query()
            ->whereNotNull('model_name')
            ->where('model_name', '!=', '')
            ->distinct()
            ->select(DB::raw($this->convertToUtf8Expression('model_name') . ' as model_name'))
            ->orderBy('model_name')
            ->pluck('model_name')
            ->all();

        $options = [];

        foreach ($models as $model) {
            $key = base64_encode((string) $model);
            $options[$key] = $this->cleanDisplayValue($model, 'N/A');
        }

        return $options;
    }

    protected function decodeModelNameFilterValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false || $decoded === '') {
            return null;
        }

        return $decoded;
    }

    protected function decodeTitleFilterValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false || $decoded === '') {
            return null;
        }

        return $decoded;
    }

    protected function getPdfUrl(): string
    {
        $filters = $this->tableFilters ?? [];
        $params = [];

        $titleValue = $filters['title_name']['value'] ?? null;
        if ($titleValue) {
            $params['title'] = $titleValue;
        }

        $modelValue = $filters['model_name']['value'] ?? null;
        if ($modelValue) {
            $params['model'] = $modelValue;
        }

        $statusValue = $filters['status']['value'] ?? null;
        if ($statusValue && $statusValue !== 'all') {
            $params['status'] = $statusValue;
        }

        return route('dashboard.inventory.pdf', $params);
    }

    protected function convertToUtf8Expression(string $column): string
    {
        return "CONVERT(CAST({$column} AS BINARY) USING utf8mb4)";
    }

    protected function cleanPdfValue(mixed $value): string
    {
        return $this->cleanDisplayValue($value, 'N/A');
    }

    protected function cleanDisplayValue(mixed $value, string $fallback): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        $string = (string) $value;

        if (function_exists('mb_check_encoding') && mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }

        if (function_exists('mb_convert_encoding')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8,ISO-8859-1,WINDOWS-1252');
        } elseif (function_exists('iconv')) {
            $converted = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $string);

            if ($converted !== false) {
                $string = $converted;
            }
        }

        if (function_exists('iconv')) {
            $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $string);

            if ($clean !== false) {
                $string = $clean;
            }
        }

        return $string;
    }
}
