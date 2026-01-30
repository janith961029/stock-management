<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuingTypeResource\Pages;
use App\Filament\Resources\IssuingTypeResource\RelationManagers;
use App\Models\IssuingType;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssuingTypeResource extends Resource
{
    protected static ?string $model = IssuingType::class;
    protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $policy = \App\Policies\IssuingTypePolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus';
     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 TextInput::make('issuing_type')
                    ->label('Issue Type')
                    ->rule(fn ($record) => Rule::unique('issuing_types', 'issuing_type')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Issue Type එක දැනටමත් system එකේ තියෙනවා.',
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

            Tables\Columns\TextColumn::make('issuing_type')
            ->label('Issue Type')
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
            'index' => Pages\ListIssuingTypes::route('/'),
            'create' => Pages\CreateIssuingType::route('/create'),
            'edit' => Pages\EditIssuingType::route('/{record}/edit'),
        ];
    }
}
