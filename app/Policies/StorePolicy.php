<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
                return $user->hasRole('super_admin') || $user->can('Store List');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Store $store): bool
    {
        return $user->hasRole('super_admin') || $user->can('Store View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Store Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        return $user->hasRole('super_admin') || $user->can('Store Update');
    }
   public function edit(User $user, Store $store): bool
    {
        return $user->hasRole('super_admin') || $user->can('Store Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): bool
    {
         return $user->hasRole('super_admin') || $user->can('Store Delete');
    }

}
