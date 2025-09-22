<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ModulePolicy
{ use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module List');
    }

    /**
     * Determine whether the user can view a permission.
     */
    public function view(User $user, Module $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module View');
    }

    /**
     * Determine whether the user can create permissions.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module Create');
    }
   public function edit(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module Edit');
    }

    /**
     * Determine whether the user can update a permission.
     */
    public function update(User $user, Module $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module Update');
    }

    /**
     * Determine whether the user can delete a permission.
     */
    public function delete(User $user, Module $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Module Delete');
    }
}
