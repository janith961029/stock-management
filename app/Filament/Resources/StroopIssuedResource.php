<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StroopIssuedResource\Pages;
use App\Filament\Resources\StroopIssuedResource\RelationManagers;
use App\Models\Store;
use App\Models\StroopIssued;
use App\Models\Titlenames;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class StroopIssuedResource extends Resource
{
    protected static ?string $model = StroopIssued::class;

    protected static ?string $navigationGroup = 'OCSO';
    protected static ?int $navigationSort = 3;
    protected static ?string $policy = \App\Policies\StroopIssuedPolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel= 'Stroop Issued';

    protected static ?string $pluralLabel = 'Issued Items';
    protected static ?string $label = ' Issued Item';
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
                Select::make('issuedstatus')
                    ->label('Status')
                    ->options([
                     'issued' => 'Issued',
               ])
                    ->default('Issued')
                    ->required(),




                TextInput::make('remarks')->label('Remarks'),

                TextInput::make('total_quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->step(1)
                    ->rules(['required', 'integer', 'min:0'])
                    ->placeholder('Quantity'),
            ]);
    }
public static function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->orderByRaw("
            CASE
                WHEN requestedstatus = 'requested' AND (confirmedstatus IS NULL OR confirmedstatus = '') THEN 1
                WHEN confirmedstatus = 'confirmed' THEN 2
                WHEN issuedstatus = 'issued' THEN 3
                ELSE 4
            END ASC
        ");
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

               Tables\Columns\TextColumn::make('store.stores')
                    ->label('Store')
                    ->searchable(isIndividual: false, isGlobal: true)
                    ->size('xs')
                    ->weight(FontWeight::Light)
                    ->fontFamily(FontFamily::Sans),

                Tables\Columns\TextColumn::make('title_names_id')
                    ->label('Title Name')
                    ->size('xs')
                    ->weight(FontWeight::Light)
                    ->fontFamily(FontFamily::Sans)
                    ->searchable(isIndividual: false, isGlobal: true),

                Tables\Columns\TextColumn::make('requestedstatus')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) =>
                        $record->issuedstatus === 'issued' ? 'Issued' :
                        ($record->confirmedstatus === 'confirmed' ? 'Confirmed' :
                        ($record->requestedstatus === 'requested' ? 'Requested' : 'Pending')))
                    ->color(fn ($record) =>
                        $record->issuedstatus === 'issued' ? 'success' :
                        ($record->confirmedstatus === 'confirmed' ? 'warning' :
                        ($record->requestedstatus === 'requested' ? 'primary' : 'secondary'))),


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

            ])
             ->filters([
    Tables\Filters\Filter::make('requested_only')
        ->label('Requested Only')
        ->query(fn ($query) =>
            $query->where('requestedstatus', 'requested')
                  ->where(function ($subQuery) {
                      $subQuery->whereNull('confirmedstatus')
                               ->orWhere('confirmedstatus', '');
                  })
        ),
        Tables\Filters\Filter::make('confirm_only')
        ->label('Confirm Only')
        ->query(fn ($query) =>
            $query->where('confirmedstatus', 'confirmed')
                  ->where(function ($subQuery) {
                      $subQuery->whereNull('issuedstatus')
                               ->orWhere('issuedstatus', '');
                  })
        ),
        Tables\Filters\Filter::make('issued_only')
        ->label('Issued Only')
        ->query(fn ($query) =>
            $query->where('requestedstatus', 'requested')
                  ->where('confirmedstatus', 'confirmed')
                  ->where('issuedstatus', 'issued')

        ),
])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    PrintBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStroopIssueds::route('/'),
            'create' => Pages\CreateStroopIssued::route('/create'),
            'edit' => Pages\EditStroopIssued::route('/{record}/edit'),
        ];
    }
}
