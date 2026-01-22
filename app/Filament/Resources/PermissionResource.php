<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Per;
use App\Models\Permission;
use App\Models\Permissions;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Infolists\Infolist as InfolistsInfolist;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Facades\Filament;

class PermissionResource extends Resource
{
    protected static ?string $model = Permissions::class;
    protected static ?string $policy = \App\Policies\PermissionPolicy::class; 
     protected static ?string $navigationLabel= 'Permission';
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'User Management';
 public static function canViewAny(): bool
{
    return Filament::auth()->user()?->hasPermissionTo('Permission View') ?? false;
}
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([

                    TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('module_id')
                        ->relationship('module', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                ])
            ]);
    }



public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            TextEntry::make('name')
                ->icon('heroicon-o-adjustments-horizontal')
                ->iconColor('primary'),
            TextEntry::make('module.name')
                ->label('Module')
                ->icon('heroicon-o-computer-desktop')
                ->iconColor('warning'), // yellow/orange
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
                TextColumn::make('module.name')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
            
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->color('success'),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\RestoreAction::make()
                    ->iconButton()
                    ->icon('fas-trash-arrow-up')
                    ->tooltip('Restore Permissions')
                    ->color('warning'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            // 'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
