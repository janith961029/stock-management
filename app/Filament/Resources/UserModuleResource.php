<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserModuleResource\Pages;
use App\Filament\Resources\UserModuleResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use App\Models\UserModule;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserModuleResource extends Resource
{
    protected static ?string $model = UserModule::class;
 protected static ?string $navigationGroup = 'User Management';
   // protected static ?string $policy = \App\Policies\UserPolicy::class;
    protected static ?int $navigationSort = 7;
    protected static ?string $policy = \App\Policies\UserModulePolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('model_id')
                  ->options(User::pluck('name', 'id'))
                    ->label('User')
                    ->live()
                    ->required()
                    ->reactive(),
                Select::make('role_id')
                   ->options(Role::pluck('name', 'id'))
                    ->label('Role')
                    ->live()
                    ->required()
                    ->reactive(),
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
            Tables\Columns\TextColumn::make('role.name')
            ->label('Role')
            ->sortable()
            ->size('xs')
            ->weight(FontWeight::Light)
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('model.name')
            ->label('Module')
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
            'index' => Pages\ListUserModules::route('/'),
            'create' => Pages\CreateUserModule::route('/create'),
            'edit' => Pages\EditUserModule::route('/{record}/edit'),
        ];
    }
}
