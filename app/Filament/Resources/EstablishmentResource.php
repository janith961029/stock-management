<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstablishmentResource\Pages;
use App\Filament\Resources\EstablishmentResource\RelationManagers;
use App\Models\Establishment;
use Filament\Forms;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstablishmentResource extends Resource
{
    protected static ?string $model = Establishment::class;
    protected static ?string $policy = \App\Policies\EstablishmentPolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 4;
     protected static ?string $navigationGroup= 'Purchase Order';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('establishment')
                    ->label('Establishment')
                    ->rule(fn ($record) => Rule::unique('establishments', 'establishment')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම establishment එක දැනටමත් system එකේ තියෙනවා.',
            ])
                    ->required(),
                TextInput::make('establishment_details')
                    ->label('Details')
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
        Tables\Columns\TextColumn::make('establishment')
            ->label('Establishment Name')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
        Tables\Columns\TextColumn::make('establishment_details')
            ->label('Details')
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
            'index' => Pages\ListEstablishments::route('/'),
            'create' => Pages\CreateEstablishment::route('/create'),
            'edit' => Pages\EditEstablishment::route('/{record}/edit'),
        ];
    }
}
