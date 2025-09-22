<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueItemResource\Pages;
use App\Filament\Resources\IssueItemResource\RelationManagers;
use Filament\Forms\Get;
use App\Models\ReciveItems;
use App\Models\serial_numbers;
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
use App\Models\IssueItem;
use App\Models\IssuingType;
use App\Models\IssuePlaces;
use App\Models\RecPlaces;
use Filament\Forms\Components\CheckboxList;
use App\Models\SignalUnit;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Grid;

class IssueItemResource extends Resource
{
    protected static ?string $model = ReciveItems::class;
    protected static ?string $modelLabel = 'Issue Items';
    protected static ?string $policy = \App\Policies\ReciveItemsPolicy::class;   
    protected static ?string $navigationGroup = 'Items';
    protected static ?int $navigationSort = 4;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNotNull('total_quantity')->where('total_quantity', '!=', '')->count();
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
                                        fn (Builder $query) => $query->where('recieved', 1)
                                            ->where(function ($query) {
                                                $query->where('issued', 0)
                                                    ->orWhereNull('issued');
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
                                        
                                        // Common Issue Details for each serial number
                                        DatePicker::make('assigned_date')
                                            ->label('Assigned Date')
                                            ->columnSpan(1),
                                            
                                        Select::make('issue_place')
                                            ->label('Issue Place')
                                            ->options(IssuePlaces::pluck('issue_place','issue_place'))
                                            ->searchable()
                                            ->columnSpan(1),
                                            
                                        Select::make('issuing_type')
                                            ->label('Issuing Type')
                                            ->options(IssuingType::pluck('issuing_type','issuing_type'))
                                            ->searchable()
                                            ->columnSpan(1),
                                            
                                        TextInput::make('job_card_number')
                                            ->label('Job Card Number')
                                            ->columnSpan(1),
                                            
                                        Select::make('signal_unit')
                                            ->label('Signal Unit')
                                            ->options(SignalUnit::pluck('sig_unit_name','sig_unit_name'))
                                            ->searchable()
                                            ->columnSpan(1),
                                        
                                        Toggle::make('issued')
                                            ->label('Issued')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->afterStateUpdated(function ($state, $record) {
                                                // Update the issued status in the database
                                                if ($record) {
                                                    $record->issued = $state ? 1 : 0;
                                                    $record->save();
                                                }
                                            })
                                            ->columnSpan(1),
                                            
                                        Hidden::make('id')
                                    ])
                                    ->columns(3)
                                    ->grid(2)
                            ])
                    ])
            ]);
    }

protected function afterSave(): void
{
    $data = $this->form->getState();

    if (!empty($data['serial_numbers'])) {
        foreach ($data['serial_numbers'] as $serialData) {
            if (!empty($serialData['id'])) {
                $serial = serial_numbers::find($serialData['id']);
                if ($serial) {
                    // Update details (except issued)
                    $serial->assigned_date = $serialData['assigned_date'] ?? null;
                    $serial->issue_place = $serialData['issue_place'] ?? null;
                    $serial->issuing_type = $serialData['issuing_type'] ?? null;
                    $serial->job_card_number = $serialData['job_card_number'] ?? null;
                    $serial->signal_unit = $serialData['signal_unit'] ?? null;

                    if (!empty($serialData['issued'])) {
                        // Only update issued = 1
                        $serial->issued = 1;
                        $serial->save();
                    } else {
                        // Toggle OFF ? only notify, do NOT save
                        \Filament\Notifications\Notification::make()
                            ->title('Not Issued')
                            ->danger()
                            ->body("Serial number {$serial->serial_number} marked as not issued.")
                            ->send();
                    }
                }
            }
        }
    }
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
                Tables\Columns\TextColumn::make('ledger_card_no')
                    ->label('Ledger Card No')
                    ->size('xs')
                    ->weight(FontWeight::Light)             
                    ->fontFamily(FontFamily::Sans),
                Tables\Columns\TextColumn::make('warrenty_expiry_date')
                    ->label('Warranty Expire Date')
                    ->size('xs')
                    ->weight(FontWeight::Light)             
                    ->fontFamily(FontFamily::Sans),
            ])
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListIssueItems::route('/'),
            'edit' => Pages\EditIssueItem::route('/{record}/edit'),
            'quantity' => Pages\Quantity::route('/{record}/quantity'),
            'qr' => Pages\QrIssueItems::route('/{record}/qr'),
        ];
    }
}