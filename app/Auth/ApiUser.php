<?php

namespace App\Auth;

use App\Models\User;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class ApiUser extends User implements FilamentUser, HasName
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct();
        $this->forceFill($attributes);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return (string) ($this->getAttribute('name') ?? $this->getAttribute('eno') ?? 'User');
    }

    public function getAuthIdentifierName(): string
    {
        return 'eno';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->getAttribute('eno');
    }

    public function getAuthPassword(): ?string
    {
        return null;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // No-op: session-only auth, no remember tokens.
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function hasRole($roles, $guard = null): bool
    {
        return true;
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        return true;
    }
}
