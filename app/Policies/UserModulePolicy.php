<?php
namespace App\Policies;

use App\Models\User;
use App\Models\UserModule;
use Illuminate\Auth\Access\Response;

class UserModulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('UserModule List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserModule $userModule): bool
    {
       return $user->hasRole('super_admin') || $user->can('UserModule View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('UserModule Create');
    }
    public function edit(User $user   ,UserModule $userModule): bool
    {
        return $user->hasRole('super_admin') || $user->can('UserModule Edit');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserModule $userModule): bool
    {
       return $user->hasRole('super_admin') || $user->can('UserModule Update') || $user->can('UserModule Edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserModule $userModule): bool
    {
        return $user->hasRole('super_admin') || $user->can('UserModule Delete');
    }


}
