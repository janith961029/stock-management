<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;

class ApiSessionUserProvider implements UserProvider
{
    private const SESSION_KEY = 'api_user';

    public function __construct(private Session $session)
    {
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        $data = $this->session->get(self::SESSION_KEY);

        if (! is_array($data)) {
            return null;
        }

        if (($data['eno'] ?? null) !== $identifier) {
            return null;
        }

        return new ApiUser($data);
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // No-op: session-only auth, no remember tokens.
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    public function rehashPasswordIfRequired(
        Authenticatable $user,
        array $credentials,
        bool $force = false
    ): void {
        // No-op: no passwords stored.
    }
}
