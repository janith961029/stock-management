<?php

namespace App\Filament\Widgets;

use App\Models\Quantities;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class DashboardOldInventoryTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 11;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Old Inventory Status')
            ->query($this->getBaseQuery())
            ->defaultSort('quantities.id', 'asc')
            ->columns([
                  Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('Barcode')
                    ->searchable(
                        isIndividual: false,
                        isGlobal: true,
                        query: function (Builder $query, string $search): Builder {
                            $cleaned = str_replace(['SN CODE : SN', 'SN CODE:SN', 'SN CODE :SN'], '', $search);
                            $cleaned = trim($cleaned);
                            $cleaned = preg_replace('/\D/', '', $cleaned);

                            return $query->where('quantities.barcode', 'like', "%{$cleaned}%")
                                ->orWhere('quantities.serial_number', 'like', "%{$cleaned}%");
                        }
                    )
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name')
                    ->searchable(['quantities.name'], isIndividual: false, isGlobal: true)
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->searchable(['quantities.serial_number'], isIndividual: false, isGlobal: true)
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
                Tables\Columns\TextColumn::make('issue_place')
                    ->label('Issue Place')
                    ->searchable(['quantities.issue_place'], isIndividual: false, isGlobal: true)
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('issuing_type')
                    ->label('Issue Type')
                    ->searchable(['quantities.issuing_type'], isIndividual: false, isGlobal: true)
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('signal_unit')
                    ->label('Signal Unit')
                    ->searchable(['quantities.signal_unit'], isIndividual: false, isGlobal: true)
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Issue Date')
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
                Tables\Columns\TextColumn::make('warrenty_expiry_date')
                    ->label('Warrenty')
                    ->formatStateUsing(fn ($state) => $this->cleanDisplayValue($state, 'N/A')),
            ])
            ->headerActions([
                Action::make('printPdf')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn () => $this->getPdfUrl())
                    ->openUrlInNewTab(),
                Action::make('viewAll')
                    ->label('View All')
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn () => \App\Filament\Resources\QuantityResource::getUrl('index'))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                SelectFilter::make('issue_place')
                    ->label('Issue Place')
                    ->options(fn (): array => $this->getIssuePlaceOptions())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $this->decodeFilterValue($data['value'] ?? null);

                        if (! $value || ! Schema::hasColumn('quantities', 'issue_place')) {
                            return $query;
                        }

                        return $query->where('quantities.issue_place', $value);
                    }),
                SelectFilter::make('issuing_type')
                    ->label('Issue Type')
                    ->options(fn (): array => $this->getIssuingTypeOptions())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value || ! Schema::hasColumn('quantities', 'issuing_type')) {
                            return $query;
                        }

                        return $query->where('quantities.issuing_type', $value);
                    }),
                SelectFilter::make('signal_unit')
                    ->label('Signal Unit')
                    ->options(fn (): array => $this->getSignalUnitOptions())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value || ! Schema::hasColumn('quantities', 'signal_unit')) {
                            return $query;
                        }

                        return $query->where('quantities.signal_unit', $value);
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->paginationPageOptions([10])
            ->defaultPaginationPageOption(10);
    }

    protected function getBaseQuery(): Builder
    {
        return Quantities::query()
            ->select([
                'quantities.id',
                'quantities.barcode',
                'quantities.name',
                'quantities.serial_number',
                'quantities.issue_place',
                'quantities.issuing_type',
                'quantities.signal_unit',
                'quantities.issue_date',
                'quantities.warrenty_expiry_date',
            ]);
    }

    protected function getIssuePlaceOptions(): array
    {
        if (! Schema::hasColumn('quantities', 'issue_place')) {
            return [];
        }

        $places = Quantities::query()
            ->whereNotNull('issue_place')
            ->where('issue_place', '!=', '')
            ->distinct()
            ->orderBy('issue_place')
            ->pluck('issue_place')
            ->all();

        $options = [];

        foreach ($places as $place) {
            $key = base64_encode((string) $place);
            $options[$key] = $this->cleanDisplayValue($place, 'N/A');
        }

        return $options;
    }

    protected function getIssuingTypeOptions(): array
    {
        if (! Schema::hasColumn('quantities', 'issuing_type')) {
            return [];
        }

        return Quantities::query()
            ->whereNotNull('issuing_type')
            ->where('issuing_type', '!=', '')
            ->distinct()
            ->orderBy('issuing_type')
            ->pluck('issuing_type', 'issuing_type')
            ->all();
    }

    protected function getSignalUnitOptions(): array
    {
        if (! Schema::hasColumn('quantities', 'signal_unit')) {
            return [];
        }

        return Quantities::query()
            ->whereNotNull('signal_unit')
            ->where('signal_unit', '!=', '')
            ->distinct()
            ->orderBy('signal_unit')
            ->pluck('signal_unit', 'signal_unit')
            ->all();
    }

    protected function decodeFilterValue(?string $value): ?string
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

        $issuePlaceValue = $filters['issue_place']['value'] ?? null;
        if ($issuePlaceValue) {
            $params['issue_place'] = $issuePlaceValue;
        }

        $issuingTypeValue = $filters['issuing_type']['value'] ?? null;
        if ($issuingTypeValue) {
            $params['issuing_type'] = $issuingTypeValue;
        }

        $signalUnitValue = $filters['signal_unit']['value'] ?? null;
        if ($signalUnitValue) {
            $params['signal_unit'] = $signalUnitValue;
        }

        return route('dashboard.old-inventory.pdf', $params);
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
