<?php
namespace App\Policies;

use App\Models\Titlenames;
use App\Models\User;

class TitlenamesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Titlenames List');
    }

    public function view(User $user, Titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames View');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Create');
    }

    public function update(User $user, Titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Update') || $user->can('titlenames Edit');
    }

    public function edit(User $user, Titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Edit');
    }

    public function delete(User $user, Titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Delete');
    }
}
