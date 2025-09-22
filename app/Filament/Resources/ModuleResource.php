<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Filament\Resources\ModuleResource\RelationManagers;
use App\Models\Module;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?string $policy = \App\Policies\ModulePolicy::class;    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                    ->minLength(2)
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord:true),
                TextInput::make('details')
                    ->label('Description')
                    ->required()
                    ->maxLength(255),
                ])

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('details')->searchable()->label('Description')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->size('xs'),
                // Tables\Actions\DeleteAction::make()->iconButton()->size('xs'),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
