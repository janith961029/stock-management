<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentTypesResource\Pages;
use App\Filament\Resources\EquipmentTypesResource\RelationManagers;
use App\Models\EquipmentTypes;
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

class EquipmentTypesResource extends Resource
{
    protected static ?string $model = EquipmentTypes::class;
    protected static ?string $navigationGroup= 'Master Data';
protected static ?string $policy = \App\Policies\EquipmentTypesPolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
         return $form->schema([
        TextInput::make('type_code')
            ->label('Type Code')
            ->required()
            ->maxLength(50)
            ->rule(fn ($record) => Rule::unique('equipment_types', 'type_code')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Type Code එක දැනටමත් system එකේ තියෙනවා.',
            ]),

        TextInput::make('equipment_name')
            ->label('Equipment Name')
            ->required()
            ->maxLength(255)
            ->rule(fn ($record) => Rule::unique('equipment_types', 'equipment_name')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Equipment Name එක දැනටමත් system එකේ තියෙනවා.',
            ]),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),

            Tables\Columns\TextColumn::make('type_code')
            ->label('Type Code')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('equipment_name')
                ->label('Equipment Name')
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
                    //PrintBulkAction::make(),
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
            'index' => Pages\ListEquipmentTypes::route('/'),
            'create' => Pages\CreateEquipmentTypes::route('/create'),
            'edit' => Pages\EditEquipmentTypes::route('/{record}/edit'),
        ];
    }
}
