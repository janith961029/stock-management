<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanResource\Pages;
use App\Filament\Resources\ScanResource\RelationManagers;
use App\Models\IssueItem;
use App\Models\IssuePlaces;
use App\Models\IssuingType;
use App\Models\Quantities;
use App\Models\SerialNumbers;
use App\Models\SignalUnit;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class ScanResource extends Resource
{
    protected static ?string $model = SerialNumbers::class;
    protected static ?string $policy = \App\Policies\ScanPolicy::class;
protected static ?string $navigationGroup= 'Summary';
 protected static ?string $navigationLabel= 'New Stock';
     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

   public static function table(Table $table): Table
    {
        return $table
           ->columns([

    Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
    Tables\Columns\TextColumn::make('barcode')
    ->formatStateUsing(fn ($state) => str_replace('SN CODE : SN', '', $state)) // display only
    ->label('Barcode')
    ->searchable(
        isIndividual: false,
        isGlobal: true,
        query: function (\Illuminate\Database\Eloquent\Builder $query, string $search): \Illuminate\Database\Eloquent\Builder {
            $cleaned = str_replace(['SN CODE : SN','SN CODE:SN','SN CODE :SN'], '', $search);
            $cleaned = trim($cleaned);
            $cleaned = preg_replace('/\D/', '', $cleaned); // only digits

            return $query->where('barcode', 'like', "%{$cleaned}%")
                         ->orWhere('serial_number', 'like', "%{$cleaned}%");
        }
    )
    ->sortable(),

 Tables\Columns\TextColumn::make('recive_item.item.title_names_id')

            ->label('Title Name')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
Tables\Columns\TextColumn::make('recive_item.model_name')

            ->label('Model Name')
            ->sortable()
            ->size('sm')
               ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

           Tables\Columns\TextColumn::make('serial_number')

            ->label('Serial Number')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

        Tables\Columns\TextColumn::make('issue_place')

            ->label('Issue Place')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

        Tables\Columns\TextColumn::make('issuing_type')

            ->label('Issue Type')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

   Tables\Columns\TextColumn::make('assigned_date')

            ->label('Issue Date')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),


         Tables\Columns\TextColumn::make('recive_item.warrenty_expiry_date')

            ->label('Warrenty')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

        Tables\Columns\TextColumn::make('signal_unit')

            ->label('Signal Unit')
            ->searchable(isIndividual: false, isGlobal: true)
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

            ])
            ->recordUrl(null)
            ->filters([

            ])
            ->headerActions([
    Tables\Actions\Action::make('go_to_custom_page')
        ->label('Scan Item')
        ->icon('heroicon-o-arrow-right')
       ->url(QuantityResource::getUrl('scan'))
        ->openUrlInNewTab(),
])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->color('success'),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //     Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
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
            'index' => Pages\ListScans::route('/'),
            'create' => Pages\CreateScan::route('/create'),
            'edit' => Pages\EditScan::route('/{record}/edit'),
        ];
    }
}

