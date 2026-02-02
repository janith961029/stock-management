<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VotesResource\Pages;
use App\Filament\Resources\VotesResource\RelationManagers;
use App\Models\Votes;
use Filament\Forms;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VotesResource extends Resource
{
    protected static ?string $model = Votes::class;
        protected static ?string $navigationGroup= 'Purchase Order';
protected static ?int $navigationSort = 3;

protected static ?string $policy = \App\Policies\VotesPolicy::class;
protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';
public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('vote_code')
                    ->label('Vote Head')
                    ->rule(fn ($record) => Rule::unique('votes', 'vote_code')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Vote Head එක දැනටමත් system එකේ තියෙනවා.',
            ])
            ->required(),
                TextInput::make('description')
                    ->label('Description')
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

            Tables\Columns\TextColumn::make('vote_code')
            ->label('Vote Head')
            ->sortable()
            ->searchable(isIndividual: false, isGlobal: true)
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('description')
                ->label('Description')
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
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
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
            'index' => Pages\ListVotes::route('/'),
            'create' => Pages\CreateVotes::route('/create'),
            'edit' => Pages\EditVotes::route('/{record}/edit'),
        ];
    }
}
