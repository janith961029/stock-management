<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SerialNumberResource\Pages;
use App\Filament\Resources\SerialNumberResource\RelationManagers;
use App\Models\SerialNumber;
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
    protected static ?string $model = ReciveItems::class;

  //  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    protected static ?string $policy = \App\Policies\SerialNumberPolicy::class; 
    protected static ?string $navigationLabel= 'Sub Items';
    protected static ?string $navigationGroup= 'Items';
public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('model_name')
                    ->label('Item Name')
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

            Forms\Components\TextInput::make('total_quantity')
                ->label('Quantity')
                ->numeric()
                ->integer()
                ->disabled()
                ->minValue(0)
                ->step(1)
                ->rules(['required', 'integer', 'min:0'])
                ->placeholder('Quantity'),

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
                    Forms\Components\Repeater::make('serial')
                        ->relationship('serial_numbers', fn (Builder $query) => $query->where('recieved', '0'))
                        ->disableItemDeletion()
                        ->schema([
                            Forms\Components\TextInput::make('serial_number')->required(),
                            Forms\Components\TextInput::make('barcode')->required(),
                                    Forms\Components\Toggle::make('recieved')
                                        ->label('Received')
                               
                                ->columns(1),
                        ])
                        ->reactive()
                        ->columns(3)
                        ->collapsed(false)
                        ->collapsible(),
                ]),
        ]);
}
protected static function getPolicy(): ?string
{
    return \App\Policies\SerialNumberPolicy::class;
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
                ->fontFamily(FontFamily::Mono), 

            Tables\Columns\TextColumn::make('items.item_code')
                ->label('Item Code')
                ->sortable()
                ->searchable(isIndividual:true,isGlobal:false)
                ->size('xs')
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('purchase_order_nos.purchase_order_no')
                ->label('Purchase Order Number')
                ->size('xs')
                ->searchable(isIndividual:true,isGlobal:false)
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),
            Tables\Columns\TextColumn::make('total_quantity')
                ->label('Quantity')
                ->size('xs')
                ->searchable(isIndividual:true,isGlobal:false)
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),
            Tables\Columns\TextColumn::make('itemprice')
                ->label('Item Price')
                ->size('xs')
                ->searchable(isIndividual:true,isGlobal:false)
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans)
                ->toggleable(isToggledHiddenByDefault:false),
            Tables\Columns\TextColumn::make('model_name')
                ->label('Model Name')
                ->size('xs')
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual:true,isGlobal:false),
        
            Tables\Columns\TextColumn::make('received_place')
                ->label('Received Place')
                ->size('xs')
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual:true,isGlobal:false),

            Tables\Columns\TextColumn::make('ledger_card_no')
            ->label('Ledger card No')
            ->size('xs')
            ->weight(FontWeight::Light)             
            ->fontFamily(FontFamily::Sans)
            ->searchable(isIndividual:true,isGlobal:false)
            ->toggleable(isToggledHiddenByDefault:true),

             Tables\Columns\TextColumn::make('manufactured_country')
             ->label('Country')
             ->size('xs')
             ->weight(FontWeight::Light)             
             ->fontFamily(FontFamily::Sans)
             ->searchable(isIndividual:true,isGlobal:false)
             ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
        ];
    }
}
