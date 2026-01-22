<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Items;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemsPolicy
{
    use HandlesAuthorization;

   public function viewAny(User $user): bool
{
    return $user->hasRole('super_admin') || $user->can('Items List');
}

public function view(User $user, Items $items): bool
{
    return $user->hasRole('super_admin')
        || $user->can('Items View');
}

public function create(User $user): bool
{
    return $user->hasRole('super_admin')
        || $user->can('Items Create');
}

public function update(User $user, Items $items): bool
{
    return $user->hasRole('super_admin')
        || $user->can('Items Update') || $user->can('Items Edit');
}

public function delete(User $user, Items $items): bool
{
    return $user->hasRole('super_admin')
        || $user->can('Items Delete');
}

public function edit(User $user, Items $items): bool
{
    return $user->hasRole('super_admin')
        || $user->can('Items Edit');
}
}
