<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuePlacesResource\Pages;
use App\Filament\Resources\IssuePlacesResource\RelationManagers;
use App\Models\IssuePlaces;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Illuminate\Validation\Rule;
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
protected static ?string $policy = \App\Policies\IssuePlacesPolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
      protected static ?string $navigationLabel= 'Establishments';
    protected static ?string $pluralLabel = 'Establishments';
    protected static ?string $label = 'Establishment';
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
                    ->rule(fn ($record) => Rule::unique('issue_places', 'issue_place')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Issue Place එක දැනටමත් system එකේ තියෙනවා.',
            ])
                    ->required(),
                TextInput::make('place_discription')
                    ->label('Place Description')

                    ->required(),
                // Toggle::make('is_q5_unit')
                //   ->label('Q5?')
                //   ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

            Tables\Columns\TextColumn::make('issue_place')
            ->label('Issue Place')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
             Tables\Columns\TextColumn::make('place_discription')
             ->searchable(isIndividual: false, isGlobal: true)
            ->label('Place Description')
            ->sortable()
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            ])
            ->recordUrl(null)
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

