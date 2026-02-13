<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignalUnitResource\Pages;
use App\Filament\Resources\SignalUnitResource\RelationManagers;
use App\Models\SignalUnit;
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
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignalUnitResource extends Resource
{
    protected static ?string $model = SignalUnit::class;
    protected static ?string $policy = \App\Policies\SignalUnitPolicy::class;
protected static ?string $navigationGroup= 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   TextInput::make('sig_unit_name')
                    ->label('Signal Unit')
                    ->rule(fn ($record) => Rule::unique('signal_units', 'sig_unit_name')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Signal Unit එක දැනටමත් system එකේ තියෙනවා.',
            ])
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
                Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

            Tables\Columns\TextColumn::make('sig_unit_name')
            ->label('Signal Unit')
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
            'index' => Pages\ListSignalUnits::route('/'),
            'create' => Pages\CreateSignalUnit::route('/create'),
            'edit' => Pages\EditSignalUnit::route('/{record}/edit'),
        ];
    }
}

