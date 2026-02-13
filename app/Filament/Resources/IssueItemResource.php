<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueItemResource\Pages;
use App\Filament\Resources\IssueItemResource\RelationManagers;
use Filament\Forms\Get;
use App\Models\ReciveItems;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Forms\Components\Toggle;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\ictcategories;
use App\Models\titlenames;
use App\Models\stores;
use App\Models\countries;
use App\Models\measures;
use App\Models\rec_places;
use App\Models\PurchaseOrderNos;
use App\Models\Items;
use App\Models\equipment_types;
use App\Models\EquipmentTypes;
use App\Models\IssueItem;
use App\Models\IssuingType;
use App\Models\IssuePlaces;
use App\Models\RecPlaces;
use App\Models\SerialNumbers;
use App\Models\ModelName;
use Filament\Forms\Components\CheckboxList;
use App\Models\SignalUnit;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Schema;

class IssueItemResource extends Resource
{
    protected static ?string $model = IssueItem::class;
    protected static ?string $modelLabel = 'Issue Items';
    protected static ?string $policy = \App\Policies\IssueItemPolicy::class;
    protected static ?string $navigationGroup = 'Items';
    protected static ?int $navigationSort = 4;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::whereNotNull('total_quantity')->where('total_quantity', '!=', '')->count();
    // }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'serial_numbers as issued_serial_numbers_count' => fn (Builder $query) => $query->where('issued', 1),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Grid::make(3)
                            ->schema([




                                TextInput::make('model_name')
                                    ->label('Item Name')
				    ->disabled()
                                    ->required(),
                                Select::make('manufactured_country')
                                    ->label('Manufactured Country')
                                    ->disabled()
                                    ->options(countries::pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                TextInput::make('ledger_card_no')
                                    ->label('Ledger Card')
                                    ->disabled(),
                                TextInput::make('total_quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->integer()
                                    ->disabled()
                                    ->minValue(0)
                                    ->step(1)
                                    ->rules(['required', 'integer', 'min:0'])
                                    ->placeholder('Quantity'),
                                Select::make('purchase_order_no')
                                    ->label('Purchase Order No')
                                    ->options(PurchaseOrderNos::pluck('purchase_order_no', 'id'))
                                    ->searchable()
                                    ->disabled()
                                    ->required(),
                                TextInput::make('itemprice')
                                    ->label('Item Price (LKR)')
                                    ->numeric()
                                    ->integer()
                                    ->disabled()
                                    ->minValue(0)
                                    ->step(1)
                                    ->rules(['required', 'integer', 'min:0'])
                                    ->placeholder('PRICE'),
                                Select::make('received_place')
                                    ->label('Received From')
                                    ->disabled()
                                    ->options(RecPlaces::pluck('Rec_place', 'id'))
                                    ->searchable()->required(),
                                DatePicker::make('received_date')
                                    ->label('Received Date ')
                                    ->disabled()
                                    ->required(),
                                DatePicker::make('warrenty_expiry_date')
                                    ->label('Warranty Expiry Date')
                                    ->disabled()
                                    ->required(),
                                TextInput::make('commander_reserve')
                                    ->disabled()
                                    ->label('Commander Reserve'),
                                TextInput::make('remarks_recieved')
                                    ->disabled()
                                    ->label('Remarks/Received'),
                            ])
                            ->columns(3),

                        Section::make('Serial Numbers with Issue Details')
                            ->schema([
                                Repeater::make('serial_numbers')
                                    ->disableItemDeletion()
                                    ->disableItemCreation()
                                   ->relationship(
    'serial_numbers',
    fn (Builder $query) => $query
        ->where('recieved', 1)

        ->where(function ($query) {
            $query->whereNull('issue_place')
                  ->orWhere('issue_place', '')
                  ->orWhere('issue_place', 0)
                  ->orWhere('issue_place', '0');
        })
)
                                    ->schema([
                                        // Serial Number Details
                                        TextInput::make('serial_number')
                                            ->disabled()
                                            ->columnSpan(1),
                                        TextInput::make('barcode')
                                            ->required()
                                            ->disabled()
                                            ->columnSpan(1),
                                           Toggle::make('issued')
    ->label('Issued')
    ->reactive() // important
    ->afterStateHydrated(function ($state, $set) {
        $set('issued', $state ?? false);
    })
                                            ->columnSpan(1),
                                        // Common Issue Details for each serial number
                                        DatePicker::make('assigned_date')
                                            ->label('Assigned Date')
                                            ->columnSpan(1)
                                            ->visible(fn (Get $get) => (bool) $get('issued'))
                                            ->dehydrated(fn (Get $get) => (bool) $get('issued'))
                                            ->required(fn (Get $get) => (bool) $get('issued')),

                                          // 🔹 Only visible if toggle ON
                                        Select::make('issuing_type')
                                            ->label('Issuing Type')
                                            ->options(IssuingType::pluck('issuing_type','issuing_type'))
                                            ->searchable()
                                            ->columnSpan(1)
                                            ->reactive()
                                            ->visible(fn (Get $get) => (bool) $get('issued'))
                                            ->dehydrated(fn (Get $get) => (bool) $get('issued'))
                                            ->required(fn (Get $get) => (bool) $get('issued')),

                                        TextInput::make('job_card_number')
                                            ->label('Job Card Number')
                                            ->columnSpan(1)
                                            ->visible(fn (Get $get) => (bool) $get('issued'))
                                            ->dehydrated(fn (Get $get) => (bool) $get('issued'))
                                            ->required(fn (Get $get) => (bool) $get('issued') && $get('issuing_type') === 'Job Card'),


                                        Select::make('signal_unit')
                                            ->label('Signal Unit')
                                            ->options(SignalUnit::pluck('sig_unit_name','sig_unit_name'))
                                            ->searchable()
                                            ->columnSpan(1)
                                            ->visible(fn (Get $get) => (bool) $get('issued'))
                                            ->dehydrated(fn (Get $get) => (bool) $get('issued'))
                                            ->required(fn (Get $get) => (bool) $get('issued')),
Select::make('issue_place')
    ->label('Issue Place')
    ->options(IssuePlaces::pluck('issue_place','issue_place'))
    ->searchable()
    ->columnSpan(1)
    ->reactive()
    ->visible(fn (Get $get) => (bool) $get('issued'))
    ->dehydrated(fn (Get $get) => (bool) $get('issued'))
    ->required(fn (Get $get) => (bool) $get('issued')),

                                        Hidden::make('id')
                                    ])
                                    ->columns(3)
                                    ->grid(2)
                            ])
                    ])
            ]);
    }






public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('id')
                ->label('#')
                ->sortable()
                ->size('sm')
                ->weight(FontWeight::Light)
                ->toggleable()
                ->searchable()
                ->fontFamily(FontFamily::Mono),

          Tables\Columns\TextColumn::make('item.item_code')
                ->label('Item Code')
                ->sortable()
                ->searchable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),
Tables\Columns\TextColumn::make('item.title_names_id')

            ->label('Title Name')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans)
            ->searchable(),
Tables\Columns\TextColumn::make('model_name')
                ->label('Model Name')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->getStateUsing(static function (IssueItem $record): ?string {
                    static $modelNameCache = null;

                    if ($modelNameCache === null) {
                        $modelNameCache = ModelName::query()
                            ->pluck('model_names', 'id')
                            ->all();
                    }

                    if (array_key_exists($record->model_name, $modelNameCache)) {
                        return $modelNameCache[$record->model_name];
                    }

                    return filled($record->model_name) ? (string) $record->model_name : null;
                })
                ->searchable(true, static function (Builder $query, string $search): Builder {
                    $search = trim($search);

                    if ($search === '') {
                        return $query;
                    }

                    $table = $query->getModel()->getTable();

                    return $query->where(function (Builder $query) use ($search, $table): void {
                        $query->where("{$table}.model_name", 'like', "%{$search}%")
                            ->orWhereExists(function ($subquery) use ($search, $table): void {
                                $subquery->selectRaw('1')
                                    ->from('model_names')
                                    ->whereColumn('model_names.id', "{$table}.model_name")
                                    ->where('model_names.model_names', 'like', "%{$search}%");
                            });
                    });
                }),

            Tables\Columns\TextColumn::make('total_quantity')
                ->label('Quantity')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:false),
                 Tables\Columns\TextColumn::make('available_count')
                ->label('Available')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->getStateUsing(static function (IssueItem $record): int {
                    $total = (int) $record->total_quantity;
                    $issued = (int) ($record->issued_serial_numbers_count ?? 0);
                    $available = $total - $issued;

                    return $available > 0 ? $available : 0;
                })
                ->formatStateUsing(static fn (int $state): string => (string) $state)
                ->searchable(true, static function (Builder $query, string $search): Builder {
                    $search = trim($search);

                    if ($search === '') {
                        return $query;
                    }

                    $table = $query->getModel()->getTable();
                    $serialForeignKey = Schema::hasColumn('serial_numbers', 'serial_id')
                        ? 'serial_id'
                        : 'recive_items_id';
                    $issuedCountSubquery = "(select count(*) from serial_numbers where serial_numbers.{$serialForeignKey} = {$table}.id and serial_numbers.issued = 1)";
                    $availableExpr = "(CAST({$table}.total_quantity AS SIGNED) - {$issuedCountSubquery})";

                    return $query->whereRaw("{$availableExpr} like ?", ["%{$search}%"]);
                }),
            Tables\Columns\TextColumn::make('item.relevantstore.stores')
                ->label('Relevant Store')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(),

            Tables\Columns\TextColumn::make('item.ictcategories.ictcategories_name')
                ->label('ICT Category')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(),

            Tables\Columns\TextColumn::make('item.equipment_types.equipment_name')
                ->label('Equipment Type')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(),

            Tables\Columns\TextColumn::make('purchase_order_nos.purchase_order_no')
                ->label('Purchase Order Number')
                ->size('xs')
                ->searchable()
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),
            Tables\Columns\TextColumn::make('total_quantity')
                ->label('Quantity')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:false),

            Tables\Columns\TextColumn::make('itemprice')
                ->label('Item Price')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:false),


           Tables\Columns\TextColumn::make('recplaces.Rec_place')
                ->label('Received Place')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(),
            Tables\Columns\TextColumn::make('ledger_card_no')
            ->label('Ledger card No')
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans)
            ->searchable()
            ->toggleable(isToggledHiddenByDefault:true),
Tables\Columns\TextColumn::make('country.name')
             ->label('Country')
             ->size('xs')
             ->weight(FontWeight::Light)
             ->fontFamily(FontFamily::Sans)
             ->searchable()
             ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\Action::make('quantity')
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->color('success')
                    ->url(fn ($record) => static::getUrl('quantity', ['record' => $record]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('qr')
                    ->icon('heroicon-o-qr-code')
                    ->iconButton()
                    ->url(fn ($record) => static::getUrl('qr', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIssueItems::route('/'),
            'edit' => Pages\EditIssueItem::route('/{record}/edit'),
            'quantity' => Pages\Quantity::route('/{record}/quantity'),
            'qr' => Pages\QrIssueItems::route('/{record}/qr'),
        ];
    }
}

