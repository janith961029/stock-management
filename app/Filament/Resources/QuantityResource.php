<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuantityResource\Pages;
use App\Filament\Resources\QuantityResource\RelationManagers;
use App\Filament\Resources\QuantityResource\Pages\QuantityResource\Scan;
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
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

use function Laravel\Prompts\select;

class QuantityResource extends Resource

{ protected static ?string $navigationGroup= 'Summary';
public static function canViewAny(): bool
{
    return Filament::auth()->user()?->hasPermissionTo('Quantity View') ?? false;
}
protected static ?string $policy = \App\Policies\QuantitiesPolicy::class;
    protected static ?string $model = Quantities::class;
  protected static ?int $navigationSort = 2;
  protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
  protected static ?string $navigationLabel= 'Old Stock';

  public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
  public static function form(Form $form): Form

    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Item Name')
                    ->required(),
                TextInput::make('barcode')
                    ->label('Barcode')
                    ->required()
                    ->numeric() // only numbers
                    ->length(6) // exactly 6 digits
                    ->rule('regex:/^\d{6}$/'),
                TextInput::make('serial_number')
                    ->label('Serial Number')
                    ->required(),
                Select::make('issue_place')
                    ->options(IssuePlaces::pluck('issue_place', 'issue_place'))
                    ->label('Issue Place')
                    ->live()
                    ->required()
                    ->reactive(),
                Select::make('signal_unit')
                    ->options(SignalUnit::pluck('sig_unit_name', 'id'))
                    ->label('Signal Unit')
                    ->live()
                    ->required()
                    ->reactive(),
                Select::make('issuing_type')
                    ->options(IssuingType::pluck('issuing_type', 'id'))
                    ->label('Issue Type')
                    ->live()
                    ->required()
                    ->reactive(),
                DatePicker::make('issue_date')
                    ->label('Issue Date'),
                DatePicker::make('warrenty_expiry_date')
                    ->label('Warrenty Expiry Date'),

            ]);

    }

    public static function table(Table $table): Table
    {
       return $table
    // ->query(function () {
    //     $query = Quantities::query();
    //     $user = Filament::auth()->user();

    //     if (! $user) {
    //         return $query;
    //     }

    //     if (! $user->hasAnyRole(['super_admin', 'panel_user', 'cso'])) {
    //         $query->where('signal_unit', $user->signal_unit_id);
    //     }

    //     return $query;
    // })
           ->columns([


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

 Tables\Columns\TextColumn::make('name')
            ->label('Item Name')
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
     Tables\Columns\TextColumn::make('issue_date')

            ->label('Issue Date')
            ->sortable()
            ->size('sm')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),

         Tables\Columns\TextColumn::make('warrenty_expiry_date')

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
            'index' => Pages\ListQuantities::route('/'),
           'create' => Pages\CreateQuantity::route('/create'),
            'edit' => Pages\EditQuantity::route('/{record}/edit'),
          'scan' => Pages\Scan::route('/scan'),
        ];
    }
}
