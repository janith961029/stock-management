<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TitlenamesResource\Pages;
use App\Filament\Resources\TitlenamesResource\RelationManagers;
use App\Models\Store;
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
use Illuminate\Validation\Rule;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;
use function Livewire\store;

class TitlenamesResource extends Resource
{
    protected static ?string $model = Titlenames::class;
    protected static ?string $policy = \App\Policies\TitlenamesPolicy::class;
    protected static ?string $navigationLabel= 'Title Names';
    protected static ?string $pluralLabel = 'Title Names';
    protected static ?string $label = 'Title Name';
 public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('title_name')
                    ->label('Title Names')
                    ->rule(fn ($record) => Rule::unique('titlenames', 'title_name')->ignore($record))
            ->validationMessages([
                    'unique' => 'මෙම Title Name එක දැනටමත් system එකේ තියෙනවා.',
                ])
                    ->required(),


                 Select::make('relevant_store_id')
                    ->options(Store::pluck('stores', 'id'))
                    ->label('Relevant Store')
                    ->live()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('title_name', null)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                 Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
            Tables\Columns\TextColumn::make('stores.stores')
            ->label('Stores')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('title_name')
            ->label('Stores')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
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
            'index' => Pages\ListTitlenames::route('/'),
            'create' => Pages\CreateTitlenames::route('/create'),
            'edit' => Pages\EditTitlenames::route('/{record}/edit'),
        ];
    }
}

