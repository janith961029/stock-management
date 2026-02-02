<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Module;
use App\Models\SignalUnit;
use App\Models\Role;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $policy = \App\Policies\UserPolicy::class;
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\TextInput::make('e_no')
                    ->label('E No')
                    ->required()
                    ->rule(fn ($record) => Rule::unique('users', 'e_no')->ignore($record))
                    ->validationMessages([
                        'unique' => 'මෙම E No එක දැනටමත් system එකේ තියෙනවා.',
                    ])
                    ->live(debounce: 700)
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                        $state = trim((string) $state);

                        if ($state === '') {
                            return;
                        }

                        $token = config('services.str.token');

                        if (! $token) {
                            return;
                        }

                        $response = Http::withOptions(['verify' => false])
                            ->get('https://str.army.lk/api/get_person/', [
                                'str-token' => $token,
                                'e_no' => $state,
                            ]);

                        if (! $response->successful()) {
                            return;
                        }

                        $payload = $response->json();
                        $record = null;

                        if (is_array($payload)) {
                            if (array_is_list($payload)) {
                                $record = $payload[0] ?? null;
                            } else {
                                $record = $payload['data'] ?? $payload['person'] ?? $payload;

                                if (is_array($record) && array_is_list($record)) {
                                    $record = $record[0] ?? null;
                                }
                            }
                        }

                        if (! is_array($record)) {
                            return;
                        }

                        foreach (['service_no', 'unit', 'rank', 'type', 'name', 'nic'] as $key) {
                            $value = data_get($record, $key);

                            if ($value !== null && $value !== '') {
                                $set($key, is_string($value) ? trim($value) : $value);
                            }
                        }

                        $set('username', $state);
                    }),

                Forms\Components\TextInput::make('name')
                    ->label('User Name')
                    ->required()
                    ->maxLength(255),






                Forms\Components\TextInput::make('service_no')
                    ->label('Service No')
                    ->maxLength(255),

                Forms\Components\TextInput::make('rank')
                    ->label('Rank')
                    ->maxLength(255),

                Forms\Components\TextInput::make('type')
                    ->label('Type')
                    ->maxLength(255),

                Forms\Components\TextInput::make('unit')
                    ->label('Unit')
                    ->maxLength(255),

                Forms\Components\TextInput::make('nic')
                    ->label('NIC')
                    ->maxLength(255),

                Forms\Components\Select::make('signal_unit_id')
                    ->label('Signal Unit')
                    ->options(SignalUnit::pluck('sig_unit_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('usr_status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->required(),
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->options(Role::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),



                // Forms\Components\TextInput::make('account_type')
                //     ->label('Account Type')
                //     ->maxLength(50),

                // Forms\Components\TextInput::make('password')
                //     ->label('Password')
                //     ->password()
                //     ->maxLength(255),

                // Forms\Components\TextInput::make('eportal_token')
                //     ->label('Eportal Token')
                //     ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
//    $user = Auth::user();

// if ($user->hasRole('super_admin') || $user->hasRole('panel_user')) {
//     $modules = Module::all();
// } elseif ($user->hasRole('manager')) {
//     // manager only see manager modules
//     $permissions = $user->getAllPermissions();
//     $moduleIds = $permissions->pluck('module_id')->unique()->filter();
//     $modules = Module::whereIn('id', $moduleIds)->get();
// } else {
//     // fallback for other roles
//     $modules = collect();
// }
     return $table
            ->columns([
                 Tables\Columns\TextColumn::make('Index')
                    ->rowIndex()
                    ->label('Ser'),
                Tables\Columns\TextColumn::make('e_no')
                    ->label('E No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable(),
                Tables\Columns\TextColumn::make('password')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remember_token')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('signalUnit.sig_unit_name')
                    ->label('Signal Unit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usr_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'Active' : 'Inactive')
                    ->color(fn ($state) => (int) $state === 1 ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('Account Type')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('eportal_token')
                    ->label('Eportal Token')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Optional: Module filter
                // Tables\Filters\SelectFilter::make('module_id')
                //     ->label('Module')
                //     ->options($modules->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);

            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //         PrintBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
