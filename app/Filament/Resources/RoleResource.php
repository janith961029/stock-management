<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;

use App\Models\Module;

use App\Models\Permissions;
use App\Models\Role;
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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Markdown; // Use Markdown component
use Filament\Forms\Components\HTML; // Use HTML component
use Filament\Forms\Components\TextArea; // Use TextArea for plain text output
use Filament\Forms\Components\Section;
use Illuminate\Validation\Rule;
use Filament\Tables\Filters\TrashedFilter;


class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
protected static ?string $policy = \App\Policies\RolePolicy::class;
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->label('Role Name')
                        ->rule(fn ($record) => Rule::unique('roles', 'name')->ignore($record))
            ->validationMessages([
                'unique' => 'මෙම Role Name එක දැනටමත් system එකේ තියෙනවා.',
            ])
                        ->unique(ignoreRecord: true)
                        ->required(),

                    Forms\Components\Group::make()
                        ->schema(function ($record) {
                            $modules = Module::with('permissions')->get();
                            $rolePermissions = $record?->permissions?->pluck('id')->toArray() ?? [];
                            $schema = [];

                            foreach ($modules as $module) {
                                $modulePermissions = $module->permissions->pluck('name', 'id');
                                if ($modulePermissions->isNotEmpty()) {
                                    $schema[] = Section::make($module->name)
                                        ->schema([
                                            CheckboxList::make("permissions")
                                                ->relationship('permissions', 'name')
                                                ->options($modulePermissions)
                                                ->default($rolePermissions) // Show currently selected
                                                ->columns(3)
                                                ->bulkToggleable()
                                        ]);
                                }
                            }

                            $noModulePermissions = Permissions::whereNull('module_id')
                                ->get()
                                ->pluck('name', 'id');

                            if ($noModulePermissions->isNotEmpty()) {
                                $schema[] = Section::make('Other Permissions')
                                    ->schema([
                                        CheckboxList::make('permissions')
                                            ->options($noModulePermissions)
                                            ->relationship('permissions', 'name')
                                            ->default($rolePermissions) // Show currently selected
                                            ->columns(3)
                                            ->bulkToggleable()
                                    ]);
                            }

                            return $schema;
                        }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
                TextColumn::make('name')
                    ->searchable(isIndividual: false, isGlobal: true)
                    ->sortable()
            ])
            ->recordUrl(null)
            ->filters([
                // TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->size('xs')->color('success'),
                Tables\Actions\EditAction::make()->iconButton()->size('xs'),
                Tables\Actions\DeleteAction::make()->iconButton()->size('xs'),
                // Tables\Actions\RestoreAction::make()
                // ->iconButton()
                // ->icon('fas-trash-arrow-up')
                // ->tooltip('Restore Permissions')
                // ->color('warning'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    // Other users do not see is 'SAdmin'.





}

