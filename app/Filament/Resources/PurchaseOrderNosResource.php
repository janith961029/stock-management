<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderNosResource\Pages;
use App\Filament\Resources\PurchaseOrderNosResource\RelationManagers;
use App\Models\Establishment;
use App\Models\PurchaseOrderNos;
use App\Models\Supplier;
use App\Models\Votes;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseOrderNosResource extends Resource
{ public static function canViewAny(): bool
{
    return auth()->user()->hasPermissionTo('PurchaseOrderNos View');
}
    protected static ?string $navigationGroup= 'Purchase Order';
    protected static ?string $policy = \App\Policies\PurchaseOrderNosPolicy::class;   
    protected static ?string $model = PurchaseOrderNos::class;
    protected static ?string $modelLabel='Purchase Orders';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            Forms\Components\Grid::make(3)
                ->schema([
    
                
                    TextInput::make('purchase_order_no')
                        ->label('Purchase Order Name')
                        ->required(),
                    Select::make('Supplier')
                        ->label('Supplier')
                        ->options(Supplier::pluck('Sup_Name', 'id'))
                        ->searchable()
                        ->required()
                    ->suffixAction(
                                Forms\Components\Actions\Action::make('newSupplier')
                                    ->icon('heroicon-o-plus')
                                    ->url(\App\Filament\Resources\SupplierResource::getUrl('create'))
                                    ->openUrlInNewTab()
                                            ),
                    Select::make('vote_code')
                        ->label('Vote code')
                        ->options(Votes::pluck('vote_code', 'id'))
                        ->searchable()
                        ->required() 
                        ->suffixAction(
                                Forms\Components\Actions\Action::make('NewVotes')
                                    ->icon('heroicon-o-plus')
                                    ->url(\App\Filament\Resources\VotesResource::getUrl('create'))
                                    ->openUrlInNewTab()
                                            ),
                    Select::make('rcvd_to')
                        ->label('Establishment')
                        ->options(Establishment::pluck('establishment', 'id'))
                        ->searchable()
                     ->suffixAction(
                                Forms\Components\Actions\Action::make('NewEstablishment')
                                    ->icon('heroicon-o-plus')
                                    ->url(\App\Filament\Resources\EstablishmentResource::getUrl('create'))
                                    ->openUrlInNewTab()
                                            ),
       
]) ])   ;   
        
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
            ->Searchable()
            ->fontFamily(FontFamily::Sans), 
            Tables\Columns\TextColumn::make('purchase_order_no')
            ->label('Item Name')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans), 
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
            'index' => Pages\ListPurchaseOrderNos::route('/'),
            'create' => Pages\CreatePurchaseOrderNos::route('/create'),
            'edit' => Pages\EditPurchaseOrderNos::route('/{record}/edit'),
        ];
    }
}
