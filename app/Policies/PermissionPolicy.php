<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permissions; // custom Permission model use කරන්න
use Illuminate\Auth\Access\HandlesAuthorization;


class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission List');
    }

    /**
     * Determine whether the user can view a permission.
     */
    public function view(User $user, Permissions $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission View');
    }

    /**
     * Determine whether the user can create permissions.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission Create');
    }
  public function edit(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission Edit');
    }

    /**
     * Determine whether the user can update a permission.
     */
    public function update(User $user, Permissions $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission Update');
    }

    /**
     * Determine whether the user can delete a permission.
     */
    public function delete(User $user, Permissions $permission): bool
    {
        return $user->hasRole('super_admin') || $user->can('Permission Delete');
    }

}
