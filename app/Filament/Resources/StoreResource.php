<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
       protected static ?string $navigationGroup= 'Master Data';
 protected static ?string $navigationLabel= 'Store';
  public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
 protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('stores')
                    ->label('Stores')
                    ->required(),
            ]);
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

            Tables\Columns\TextColumn::make('stores')
            ->label('Stores')
            ->sortable()
            ->size('xs')
            ->weight(FontWeight::Light)             
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
                    PrintBulkAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
