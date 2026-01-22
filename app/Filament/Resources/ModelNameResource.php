<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelNameResource\Pages;
use App\Filament\Resources\ModelNameResource\RelationManagers;
use App\Models\ModelName;
use App\Models\Titlenames;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Illuminate\Validation\Rule;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelNameResource extends Resource
{
    protected static ?string $model = ModelName::class;

     protected static ?string $navigationGroup= 'Master Data';
protected static ?string $policy = \App\Policies\ModelNamePolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                  TextInput::make('model_names')
                    ->label('Model Names')
                    ->rule(fn ($record) => Rule::unique('model_names', 'model_names')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Model Name එක දැනටමත් system එකේ තියෙනවා.',
            ])
                    ->required(),
                // Select::make('title_names_id')
                //     ->options(Titlenames::pluck('title_name', 'id'))
                //     ->label('Title Name')
                //     ->live()
                //     ->required()
                //     ->reactive(),
            //         ->afterStateUpdated(fn (Forms\Set $set) => $set('model_names', null)),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

        Tables\Columns\TextColumn::make('model_names')
            ->label('Model Name')
            ->sortable()

            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListModelNames::route('/'),
            'create' => Pages\CreateModelName::route('/create'),
            'edit' => Pages\EditModelName::route('/{record}/edit'),
        ];
    }
}
