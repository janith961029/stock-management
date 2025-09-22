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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IctCategoriesResource extends Resource
{
    protected static ?string $model = IctCategories::class;
    protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ictcategories_name')
                    ->label('Ict Categories')
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

            Tables\Columns\TextColumn::make('ictcategories_name')
            ->label('ICT Categories')
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
            'index' => Pages\ListIctCategories::route('/'),
            'create' => Pages\CreateIctCategories::route('/create'),
            'edit' => Pages\EditIctCategories::route('/{record}/edit'),
        ];
    }
}
