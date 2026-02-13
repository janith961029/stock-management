<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderNosResource\Pages;
use App\Filament\Resources\PurchaseOrderNosResource\RelationManagers;
use App\Models\Establishment;
use App\Models\PurchaseOrderNos;
use App\Models\Supplier;
use App\Models\Votes;
use App\Models\IssuePlaces;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action as TableAction;

class PurchaseOrderNosResource extends Resource
{ public static function canViewAny(): bool
{
    return Filament::auth()->user()?->hasPermissionTo('PurchaseOrderNos View') ?? false;
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

                        ->rule(fn ($record) => Rule::unique('purchase_order_nos', 'purchase_order_no')->ignore($record))
            ->validationMessages([
                        'unique' => 'මෙම Purchase Order Name එක දැනටමත් system එකේ තියෙනවා.',
                    ])
                    ->required(),
                    Select::make('sup_id')
                        ->label('Supplier')
                        ->options(Supplier::pluck('supplier', 'id'))
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
                        ->options(IssuePlaces::pluck('issue_place', 'id'))
                        ->searchable(),
                    //  ->suffixAction(
                    //             Forms\Components\Actions\Action::make('NewEstablishment')
                    //                 ->icon('heroicon-o-plus')
                    //                 ->url(\App\Filament\Resources\EstablishmentResource::getUrl('create'))
                    //                 ->openUrlInNewTab()
                    //                         ),
                 TextInput::make('amount')

                        ->label('Amount')
                        ->prefix('Rs.')
                        ->numeric()
                        ->required(),
                TextInput::make('p_order_remarks')
                        ->label('Purchase Order Remarks'),


]) ])   ;

}


    public static function table(Table $table): Table
    {
        return $table
           ->columns([
            Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
            Tables\Columns\TextColumn::make('purchase_order_no')
            ->label('Item Name')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),


            Tables\Columns\TextColumn::make('supplier.supplier')
            ->label('Supplier Name')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('votes.vote_code')
            ->label('Vote Code')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),

            Tables\Columns\TextColumn::make('issuePlace.issue_place')
            ->label('Establishment')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),

             Tables\Columns\TextColumn::make('amount')
            ->label('Amount')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('p_order_remarks')
            ->label('Purchase Order Remarks')
            ->wrap()
            ->size('sm')
            ->weight(FontWeight::Light)             // lighter font
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('confirmed')
            ->label('Status')
            ->badge()
            ->formatStateUsing(fn ($state): string => ((int) $state === 1) ? 'Verified' : 'Pending')
            ->color(fn ($state): string => ((int) $state === 1) ? 'success' : 'warning')
            ->sortable(),
            ])
            ->defaultSort('confirmed', 'asc')
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->actions([
    TableAction::make('confirm')
        ->icon('heroicon-o-check-circle')
        ->iconButton()
        ->color('success')
        ->tooltip('Confirm')
        ->visible(fn (PurchaseOrderNos $record): bool =>
            ! (bool) $record->confirmed
            && (Filament::auth()->user()?->hasRole('OCSO OC') ?? false)
        )
        ->requiresConfirmation()
        ->action(fn (PurchaseOrderNos $record) => $record->update([
            'confirmed' => true,
            'confirmed_user' => Filament::auth()->id(),
            'confirmed_date' => now(),
        ])),
    Tables\Actions\ViewAction::make()
        ->iconButton()
        ->color('success')
        ->form([
            Forms\Components\Grid::make(3)->schema([

                TextInput::make('purchase_order_no')
                    ->label('Purchase Order Name')
                    ->disabled(),

                Select::make('sup_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('supplier', 'id'))
                    ->searchable()
                    ->disabled(),
                    // ✅ suffixAction දාන්නේ නැහැ

                Select::make('vote_code')
                    ->label('Vote code')
                    ->options(Votes::pluck('vote_code', 'id'))
                    ->searchable()
                    ->disabled(),

                Select::make('rcvd_to')
                    ->label('Establishment')
                    ->options(Establishment::pluck('establishment', 'id'))
                    ->searchable()
                    ->disabled(),
            ]),
        ]),

    Tables\Actions\EditAction::make()->iconButton(),
    Tables\Actions\DeleteAction::make()->iconButton(),
    ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //     Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        // $user = Filament::auth()->user();

        // if ($user?->hasRole('OCSO Clerk')) {
        //     $query->where('confirmed', 1);
        // } elseif ($user?->hasRole('OCSO OC')) {
        //     $query->where('confirmed', 0);
        // }

        return $query;
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

