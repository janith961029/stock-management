<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecPlacesResource\Pages;
use App\Filament\Resources\RecPlacesResource\RelationManagers;
use App\Models\RecPlaces;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Illuminate\Validation\Rule;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecPlacesResource extends Resource
{
    protected static ?string $model = RecPlaces::class;
    protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $policy = \App\Policies\RecPlacesPolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('Rec_place')
                    ->label('Recieved Place')
                    ->rule(fn ($record) => Rule::unique('rec_places', 'Rec_place')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Recieved Place එක දැනටමත් system එකේ තියෙනවා.',
            ])
            ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

            Tables\Columns\TextColumn::make('Rec_place')
            ->label('Issue Type')
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
            'index' => Pages\ListRecPlaces::route('/'),
            'create' => Pages\CreateRecPlaces::route('/create'),
            'edit' => Pages\EditRecPlaces::route('/{record}/edit'),
        ];
    }
}
