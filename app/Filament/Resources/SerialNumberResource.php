<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SerialNumberResource\Pages;
use App\Filament\Resources\SerialNumberResource\RelationManagers;
use App\Models\SerialNumbers;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\ReciveItems;
use App\Models\IctCategories;
use App\Models\titlenames;
use App\Models\Store;
use App\Models\measures;
use App\Models\RecPlaces;
use App\Models\PurchaseOrderNos;
use App\Models\Items;
use App\Models\EquipmentTypes;
use App\Models\IssueItem;
use App\Models\LedgerCard;
use App\Models\countries;
use App\Models\Serial;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\HtmlColumn;
use DNS1D;

class SerialNumberResource extends Resource
{
    protected static ?string $model = Serial::class;

  //  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    protected static ?string $policy = \App\Policies\SerialPolicy::class;
    protected static ?string $navigationLabel= 'Sub Items';
    protected static ?string $navigationGroup= 'Items';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('serial_numbers')
            ->orderByRaw(
                'CASE WHEN total_quantity IS NOT NULL AND total_quantity > 0 AND serial_numbers_count >= total_quantity THEN 1 ELSE 0 END ASC'
            );
    }

    private static function getRemainingSerialQuantity(?Serial $record, int $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        if (!$record || !$record->exists) {
            return $total;
        }

        $existingCount = $record->serial_numbers()->count();
        $remaining = $total - $existingCount;

        return $remaining > 0 ? $remaining : 0;
    }
public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('model_name')
                ->label('Item Name')
                ->disabled()
                ->required(),

            Forms\Components\Select::make('manufactured_country')
                ->label('Manufactured Country')
                ->disabled()
                ->options(countries::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('ledger_card_no')
                ->label('Ledger Card')
                ->disabled(),

            Forms\Components\Select::make('purchase_order_no')
                ->label('Purchase Order No')
                ->options(PurchaseOrderNos::pluck('purchase_order_no', 'id'))
                ->searchable()
                ->disabled()
                ->required(),

            Forms\Components\TextInput::make('itemprice')
                ->label('Item Price (LKR)')
                ->numeric()
                ->integer()
                ->disabled()
                ->minValue(0)
                ->step(1)
                ->rules(['required', 'integer', 'min:0'])
                ->placeholder('PRICE'),

            Forms\Components\Select::make('received_place')
                ->label('Received From')
                ->disabled()
                ->options(RecPlaces::pluck('Rec_place', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('received_date')
                ->label('Received Date')
                ->disabled()
                ->required(),

            Forms\Components\DatePicker::make('warrenty_expiry_date')
                ->label('Warranty Expiry Date')
                ->disabled()
                ->required(),

            Forms\Components\TextInput::make('commander_reserve')
                ->disabled()
                ->label('Commander Reserve'),

            Forms\Components\TextInput::make('remarks_recieved')
                ->disabled()
                ->label('Remarks/Received'),

            // Serial Numbers Section
            Forms\Components\Section::make('Serial Numbers')
                ->schema([
                    Forms\Components\TextInput::make('total_quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->integer()
                        ->disabled()
                        ->minValue(0)
                        ->step(1)
                        ->rules(['required', 'integer', 'min:0'])
                        ->placeholder('Quantity'),
                    Forms\Components\Repeater::make('serial')
                        ->relationship('serial_numbers', fn (Builder $query) => $query->where('recieved', '0'))
                        ->disableItemDeletion()
                        ->schema([
                            Forms\Components\TextInput::make('serial_number')->required(),
                            Forms\Components\TextInput::make('barcode')
                                ->required()
                                ->numeric()
                                ->integer()
                                ->minValue(30000)
                                ->rules(['required', 'integer', 'min:30000']),
                            Forms\Components\Toggle::make('recieved')
                                ->label('Received')
                                ->columns(1)

                                ->default(true),
                        ])
                        ->reactive()
                        ->columns(3)
                        ->collapsed(false)
                        ->collapsible()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $total = $get('total_quantity');
                            if ($total !== null && count($state) > $total) {
                                // Remove extra items
                                $set('serial', array_slice($state, 0, $total));
                            }
                        })
                        ->minItems(fn (Get $get, ?Serial $record) => static::getRemainingSerialQuantity(
                            $record,
                            (int) $get('total_quantity')
                        ))
                        ->maxItems(fn (Get $get, ?Serial $record) => static::getRemainingSerialQuantity(
                            $record,
                            (int) $get('total_quantity')
                        ))
                        ->disableItemCreation(fn (Get $get, ?Serial $record) => static::getRemainingSerialQuantity(
                            $record,
                            (int) $get('total_quantity')
                        ) <= 0)
                        // Custom validation for all 'recieved' toggles should be ON must be handled in a FormRequest or model event.
                ]),
        ]);
}
protected static function getPolicy(): ?string
{
    return \App\Policies\SerialPolicy::class;
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->getStateUsing(static function (Serial $record): string {
                    $total = (int) $record->total_quantity;
                    $received = (int) $record->serial_numbers_count;

                    return ($total > 0 && $received >= $total) ? 'Recieved' : 'Pending';
                })
                ->color(static fn (string $state): string => $state === 'Recieved' ? 'warning' : 'gray'),

            Tables\Columns\TextColumn::make('item.item_code')
                ->label('Item Code')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),
Tables\Columns\TextColumn::make('title.title_names_id')

            ->label('Title Name')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

             Tables\Columns\TextColumn::make('model_name')
                ->label('Model Name')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual: false, isGlobal: true),

            Tables\Columns\TextColumn::make('item.relevantstore.stores')
                ->label('Relevant Store')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('item.ictcategories.ictcategories_name')
                ->label('ICT Category')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('item.equipment_types.equipment_name')
                ->label('Equipment Type')
                ->sortable()
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('purchase_order_nos.purchase_order_no')
                ->label('Purchase Order Number')
                ->size('xs')
                ->searchable(isIndividual: false, isGlobal: true)
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),



            Tables\Columns\TextColumn::make('total_quantity')
                ->label('Quantity')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),
            Tables\Columns\TextColumn::make('itemprice')
                ->label('Item Price')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),

            Tables\Columns\TextColumn::make('recplaces.Rec_place')
                ->label('Received Place')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual: false, isGlobal: true),
            Tables\Columns\TextColumn::make('ledger_card_no')
            ->label('Ledger card No')
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans)
            ->searchable(isIndividual: false, isGlobal: true)
            ->toggleable(isToggledHiddenByDefault:true),

             Tables\Columns\TextColumn::make('country.name')
             ->label('Country')
             ->size('xs')
             ->weight(FontWeight::Light)
             ->fontFamily(FontFamily::Sans)
             ->searchable(isIndividual: false, isGlobal: true)
             ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('qr')
                    ->icon('heroicon-o-qr-code')
                    ->iconButton()
                    ->url(fn ($record) => static::getUrl('qr', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    PrintBulkAction::make(),
                ]),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSerialNumbers::route('/'),
            'create' => Pages\CreateSerialNumber::route('/create'),
            'edit' => Pages\EditSerialNumber::route('/{record}/edit'),
            'qr' => Pages\QrSerialNumbers::route('/{record}/qr'),
        ];
    }
}
