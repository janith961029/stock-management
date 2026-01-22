<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CsoapprovalResource\Pages;
use App\Models\Csoapproval;
use App\Models\Store;
use App\Models\Titlenames;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class CsoapprovalResource extends Resource
{
    protected static ?string $model = Csoapproval::class;
    protected static ?string $navigationGroup = 'OCSO';
    protected static ?string $policy = \App\Policies\CsoapprovalPolicy::class;
     protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel= 'Comfirm Items';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('relevant_store_id')
                    ->options(Store::pluck('stores', 'id'))
                    ->label('Relevant Store')
                    ->live()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('title_name', null)),

                Select::make('title_names_id')
                    ->label('Title Name')
                    ->options(Titlenames::pluck('title_name', 'id'))
                    ->placeholder(fn(Forms\Get $get) =>
                        empty($get('relevant_store_id')) ? 'First select Store' : 'Select Title Name')
                    ->options(fn (Forms\Get $get) =>
                        Titlenames::where('relevant_store_id', $get('relevant_store_id'))
                            ->pluck('title_name', 'title_name'))
                    ->required(),

                TextInput::make('model_name')
                    ->label('Model Name')
                    ->required(),
                Select::make('confirmedstatus')
    ->label('Status')
    ->options([
        'confirmed' => 'confirmed',
    ])
    ->default('requested') // default value when creating
                  // field hidden in form
    ->required(),
    //             Select::make('requestedstatus')
    //                ->label('Status')
    //                ->options([
    //     'requested' => 'Requested',
    //     'issued' => 'Issued',

    // ])
    // ->default('requested')  // default value
    // ->required(),



    // must select one
                TextInput::make('remarks')->label('Remarks'),

                TextInput::make('total_quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->step(1)
                    ->rules(['required', 'integer', 'min:0'])
                    ->placeholder('Quantity'),

            //     // ------------------- Requested -------------------
            //     Select::make('requestedstatus')
            //         ->label('Requested Status')
            //         ->options([
            //             'requested' => 'Requested',
            //             'pending' => 'Pending',
            //         ])
            //         ->default('requested')
            //         ->required(),

            //     TextInput::make('requesteduser')->label('Requested By')->disabled(),
            //     DatePicker::make('requesteddate')->label('Requested Date')->disabled(),

            //     // ------------------- Confirmed -------------------
            //     Select::make('confirmedstatus')
            //         ->label('Confirmed Status')
            //         ->options([
            //             'confirmed' => 'Confirmed',
            //             'pending' => 'Pending',
            //         ])
            //         ->default('pending'),

            //     TextInput::make('confirmeduser')->label('Confirmed By')->disabled(),
            //     DatePicker::make('confirmeddate')->label('Confirmed Date')->disabled(),

            //     // ------------------- Issued -------------------
            //     Select::make('issuedstatus')
            //         ->label('Issued Status')
            //         ->options([
            //             'issued' => 'Issued',
            //             'pending' => 'Pending',
            //         ])
            //         ->default('pending'),

            //     TextInput::make('issueduser')->label('Issued By')->disabled(),
            //     DatePicker::make('issueddate')->label('Issued Date')->disabled(),
            ]);
    }
protected static function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->where('requestedstatus', 'requested')
        ->where('confirmedstatus', 'confirmed')
        ->where(function ($query) {
            $query->whereNull('issuedstatus')
                  ->orWhere('issuedstatus', '');
        });
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

                Tables\Columns\TextColumn::make('relevant_store_id')
                    ->label('Store')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->size('xs')
                    ->weight(FontWeight::Light)
                    ->fontFamily(FontFamily::Sans),

                Tables\Columns\TextColumn::make('title_names_id')
                    ->label('Title Name')
                    ->size('xs')
                    ->weight(FontWeight::Light)
                    ->fontFamily(FontFamily::Sans)
                    ->searchable(isIndividual: true, isGlobal: false),

    Tables\Columns\TextColumn::make('requestedstatus')
    ->label('Status')
    ->badge()
    ->formatStateUsing(fn ($record) =>
        ($record->requestedstatus === 'requested' && empty($record->confirmedstatus))
            ? 'Requested'
            : ($record->confirmedstatus === 'confirmed' ? 'Confirmed' :
              ($record->issuedstatus === 'issued' ? 'Issued' : 'Pending'))
    )
    ->color(fn ($record) =>
        ($record->requestedstatus === 'requested' && empty($record->confirmedstatus))
            ? 'primary'
            : ($record->confirmedstatus === 'confirmed' ? 'warning' :
              ($record->issuedstatus === 'issued' ? 'success' : 'secondary'))
    )



                ])
            ->actions([
               Tables\Actions\EditAction::make()->iconButton(),

                Tables\Actions\Action::make('markRequested')
                    ->label('Mark as Requested')
                    ->color('primary')
                    ->action(fn($record) => $record->update([
                        'requestedstatus' => 'requested',
                        'requesteduser'   => Filament::auth()->user()?->name ?? 'System',
                        'requesteddate'   => now(),
                    ]))
                    ->visible(fn($record) => $record->requestedstatus !== 'requested'),

                // Tables\Actions\Action::make('markConfirmed')
                //     ->label('Mark as Confirmed')
                //     ->color('warning')
                //     ->action(fn($record) => $record->update([
                //         'confirmedstatus' => 'confirmed',
                //         'confirmeduser'   => auth()->user()->name,
                //         'confirmeddate'   => now(),
                //     ]))
                //     ->visible(fn($record) => $record->confirmedstatus !== 'confirmed'),

                // Tables\Actions\Action::make('markIssued')
                //     ->label('Mark as Issued')
                //     ->color('success')
                //     ->action(fn($record) => $record->update([
                //         'issuedstatus' => 'issued',
                //         'issueduser'   => auth()->user()->name,
                //         'issueddate'   => now(),
                //     ]))
                //     ->visible(fn($record) => $record->issuedstatus !== 'issued'),
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
            'index'  => Pages\ListCsoapprovals::route('/'),
            'create' => Pages\CreateCsoapproval::route('/create'),
            'edit'   => Pages\EditCsoapproval::route('/{record}/edit'),
        ];
    }
}
