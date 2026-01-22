<?php
namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Role List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,Role $role): bool
    {

         return $user->hasRole('super_admin') || $user->can('Role View');
    }

  public function edit(User $user, Role $role): bool
    {

         return $user->hasRole('super_admin') || $user->can('Role Edit');
    }
    public function create(User $user): bool
    {

         return $user->hasRole('super_admin') || $user->can('Role Create');
    }

    public function update(User $user, Role $role): bool
    {

         return $user->hasRole('super_admin') || $user->can('Role Update') || $user->can('Role Edit');
    }


    public function delete(User $user, Role $role): bool
    {

         return $user->hasRole('super_admin') || $user->can('Role Delete');
    }


}
