<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReciveItemsResource\Pages;
use App\Filament\Resources\ReciveItemsResource\RelationManagers;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use App\Models\ReciveItems;
use App\Models\IctCategories;
use App\Models\Titlenames;
use App\Models\Store;
use App\Models\Measures;
use App\Models\ModelName;
use App\Models\RecPlaces;
use App\Models\PurchaseOrderNos;
use App\Models\Items;
use App\Models\EquipmentTypes;
use App\Models\IssueItem;
use App\Models\LedgerCard;
use Illuminate\Support\Facades\Gate;
use App\Models\countries;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\HtmlColumn;
use DNS1D;
class ReciveItemsResource extends Resource
{
    protected static ?string $model =ReciveItems::class;
    protected static ?string $modelLabel='Recieved Items';
    protected static ?string $policy = \App\Policies\RecieveItemPolicy::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel= 'Recieved Items';
    protected static ?string $navigationGroup= 'Items';



    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Grid::make(3)
                ->schema([














                    Forms\Components\Select::make('relevant_store_id')
                        ->options(Store::pluck('stores', 'id'))
                        ->label('Relevant Store')
                        ->disabled()
                        ->searchable()->required(),

                    Forms\Components\Select::make('ict_category_id')
                        ->label('ICT Category')
                        ->disabled()
                        ->options(IctCategories::pluck('ictcategories_name', 'id'))
                        ->searchable()->required(),

                    Forms\Components\Select::make('equipment_types_id')
                        ->label('Equipment Type')
                        ->disabled()
                        ->options(EquipmentTypes::pluck('equipment_name', 'id'))
                        ->searchable()->required(),


                    Forms\Components\Select::make('title_names_id')
                        ->label('Title Name')
                        ->disabled()
                        ->options(Titlenames::pluck('title_name', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive(),
//ICT Category

                    Forms\Components\TextInput::make('item_code')
                        ->disabled()
                        ->label('Item Code'),



                ])
                ->columns(3),

        Section::make('Add Item Bulk')
        ->schema([


        Repeater::make('Recieving Details')
       ->relationship('item_details', fn (Builder $query) =>$query->whereNull('total_quantity'))
//      ->relationship('item_details')
            ->schema([


                Forms\Components\Select::make('model_name')
                    ->label('Model Name')
                    ->searchable()
                    ->required()
                  ->options(ModelName::pluck('model_names', 'id'))
                   ->suffixAction(
                                Forms\Components\Actions\Action::make('NewModel')
                                    ->icon('heroicon-o-plus')
                                    ->url(\App\Filament\Resources\ModelNameResource::getUrl('create'))
                                    ->openUrlInNewTab()
                                            ),


                Forms\Components\Select::make('manufactured_country')
                        ->label('Manufactured  Country')
                        ->options(countries::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                TextInput::make('ledger_card_no')
                ->label('Ledger Card'),


                TextInput::make('total_quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->step(1)
                    ->rules(['required', 'integer', 'min:0'])
                    ->placeholder('Quantity'),
                Select::make('purchase_order_no')
                    ->label('Purchase Order No')
                    ->options(PurchaseOrderNos::pluck('purchase_order_no', 'id'))
                    ->searchable()->required(),

                TextInput::make('itemprice')
                    ->label('Item Price (LKR)')

                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->step(1)
                    ->rules(['required', 'integer', 'min:0'])
                    ->placeholder('PRICE'),

                Select::make('received_place')
                    ->label('Received From')
                    ->options(RecPlaces::pluck('Rec_place', 'id'))
                    ->searchable()->required(),

                DatePicker::make('received_date')
                    ->label('Received Date ')
                    ->minDate(now())
                    ->maxDate(now())
                    ->rules(['date_equals:today'])
                    ->required(),
                DatePicker::make('warrenty_expiry_date')
                    ->label('Warranty Expiry Date')
                    ->minDate(now())
                    ->rules(['after_or_equal:today'])
                    ->required(),


                TextInput::make('remarks_recieved')
                  ->label('Remarks'),
            ])
            ->columns(3),
    ])]);
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

            Tables\Columns\TextColumn::make('item_code')
                ->label('Item Code')
                ->sortable()
                ->searchable(isIndividual:true,isGlobal:false)
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('equipment_types.equipment_name')
                ->label('Equipment Type')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual:true,isGlobal:false),

            Tables\Columns\TextColumn::make('title_names_id')
                ->label('Title Name')
                ->size('xs')
                ->weight(FontWeight::Light)
                ->fontFamily(FontFamily::Sans)
                ->searchable(isIndividual:true,isGlobal:false),
            Tables\Columns\TextColumn::make('ictcategories.ictcategories_name')
             ->label('ICT Category')
             ->size('xs')
             ->weight(FontWeight::Light)
             ->fontFamily(FontFamily::Sans)
             ->searchable(isIndividual:true,isGlobal:false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->color('success'),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),




            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
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
            'index' => Pages\ListReciveItems::route('/'),
           //'create' => Pages\CreateReciveItems::route('/create'),
           // 'edit' => Pages\EditReciveItems::route('/{record}/edit'),
            'qr' => Pages\QrReciveItems::route('/{record}/qr'),

        ];
    }
}
