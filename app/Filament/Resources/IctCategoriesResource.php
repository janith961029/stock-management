<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IctCategoriesResource\Pages;
use App\Filament\Resources\IctCategoriesResource\RelationManagers;
use App\Models\IctCategories;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
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
use Filament\Facades\Filament;

class IctCategoriesResource extends Resource
{
    protected static ?string $model = IctCategories::class;
    protected static ?string $policy = \App\Policies\IctCategoriesPolicy::class;
    protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()?->can('IctCategories List') ?? false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ictcategories_name')
                    ->label('Ict Categories')
                    ->rule(fn ($record) => Rule::unique('ictcategories', 'ictcategories_name')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Ict Categories එක දැනටමත් system එකේ තියෙනවා.',
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

            Tables\Columns\TextColumn::make('ictcategories_name')
            ->label('ICT Categories')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
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
            'index' => Pages\ListIctCategories::route('/'),
            'create' => Pages\CreateIctCategories::route('/create'),
            'edit' => Pages\EditIctCategories::route('/{record}/edit'),
        ];
    }
}
