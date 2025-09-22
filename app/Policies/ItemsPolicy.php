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
    return $user->hasRole('super_admin') 
        || $user->can('Items List') 
        || $user->can('RecieveItems List');
}

public function view(User $user, Items $items): bool
{
    return $user->hasRole('super_admin') 
        || $user->can('Items View') 
        || $user->can('RecieveItems View');
}

public function create(User $user): bool
{
    return $user->hasRole('super_admin') 
        || $user->can('Items Create') 
        || $user->can('RecieveItems Create');
}

public function update(User $user, Items $items): bool
{
    return $user->hasRole('super_admin') 
        || $user->can('Items Update') 
        || $user->can('RecieveItems Update');
}

public function delete(User $user, Items $items): bool
{
    return $user->hasRole('super_admin') 
        || $user->can('Items Delete') 
        || $user->can('RecieveItems Delete');
}

public function edit(User $user, Items $items): bool
{
    return $user->hasRole('super_admin') 
        || $user->can('Items Edit') 
        || $user->can('RecieveItems Edit');
}
}
