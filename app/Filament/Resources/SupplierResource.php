<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $policy = \App\Policies\SupplierPolicy::class; 
    protected static ?string $navigationGroup= 'Purchase Order';
    protected static ?int $navigationSort =2;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    
public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('Sup_Name')
                    ->label('Supplier Name')
                    ->required(),
                TextInput::make('Addrs')
                    ->label('Address')
                    ->required(),
                TextInput::make('Tel')
                    ->label('Contact No')
                    ->required(),
                TextInput::make('Fax')
                    ->label('Fax No')
                    ->required(),
                TextInput::make('Email')
                    ->label('Email')
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

            Tables\Columns\TextColumn::make('Sup_Name')
            ->label('Supplier Name')
            ->sortable()
            ->searchable(isIndividual:true,isGlobal:false)
            ->size('xs')
            ->weight(FontWeight::Light)             
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('Addrs')
                ->label('Address')
                ->size('xs')
                ->weight(FontWeight::Light)             
                ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('Tel')
            ->label('Tele')
            ->sortable()
            ->searchable(isIndividual:true,isGlobal:false)
            ->size('xs')
            ->weight(FontWeight::Light)             
            ->fontFamily(FontFamily::Sans),
            Tables\Columns\TextColumn::make('Fax')
                ->label('Fax')
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
                ]),  ]);
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
