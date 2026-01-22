<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\Role;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    /**
     * @var view-string
     */
    protected static string $view = 'filament.pages.auth.login';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            \Filament\Forms\Components\TextInput::make('eno')
                ->label('ENO')
                ->required()
                ->autofocus(),

            \Filament\Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),
        ]);
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $response = Http::withOptions(['verify' => false])
            ->post('https://auth.army.lk/eportal/api/dailymail_login', [
                'username' => $data['eno'],
                'password' => $data['password'],
                'api_key'  => config('services.eportal.api_key'),
            ]);

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'eno' => 'Invalid Eportal username or password.',
            ]);
        }

        $eno = (string) data_get($response->json(), 'person.0.eno');
        $token = data_get($response->json(), 'authorization.token');

        if (! $eno || ! $token) {
            throw ValidationException::withMessages([
                'eno' => 'Eportal response invalid.',
            ]);
        }

        $name = data_get($response->json(), 'person.0.name');

        $user = User::where('e_no', $eno)->first();

        if (! $user) {
            Notification::make()
                ->title('Account not found')
                ->body('Contact your administrator to create your account.')
                ->danger()
                ->send();

            return null;
        }

        if ((int) $user->usr_status !== 1) {
            Notification::make()
                ->title('Account inactive')
                ->body('Contact your administrator to activate your account.')
                ->danger()
                ->send();

            return null;
        }

        if (! $user->name) {
            $user->name = $name ?: $eno;
        }

        $user->eportal_token = $token;
        $user->save();

        if ($user->role_id) {
            $role = Role::find($user->role_id);

            if ($role) {
                $user->syncRoles([$role]);
            }
        }

        auth()->guard(config('filament.auth.guard'))->login($user, remember: false);
        session()->regenerate();

        return app(LoginResponse::class);
    }

}
