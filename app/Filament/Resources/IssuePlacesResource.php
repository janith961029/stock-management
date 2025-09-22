<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuePlacesResource\Pages;
use App\Filament\Resources\IssuePlacesResource\RelationManagers;
use App\Models\IssuePlaces;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssuePlacesResource extends Resource
{
    protected static ?string $model = IssuePlaces::class;
protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('issue_place')
                    ->label('Issue Place')
                    ->required(),
                TextInput::make('place_discription')
                    ->label('Place Discription')
                    ->required(),
                Toggle::make('is_q5_unit')
                  ->label('Q5?')
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

            Tables\Columns\TextColumn::make('issue_place')
            ->label('Issue Place')
            ->sortable()
            ->size('xs')
            ->weight(FontWeight::Light)             
            ->fontFamily(FontFamily::Sans),
             Tables\Columns\TextColumn::make('place_discription')
            ->label('Place Discription')
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
            'index' => Pages\ListIssuePlaces::route('/'),
            'create' => Pages\CreateIssuePlaces::route('/create'),
            'edit' => Pages\EditIssuePlaces::route('/{record}/edit'),
        ];
    }
}
