<?php

namespace App\Policies;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class UserPolicy

{
    /**
     * Determine whether the user can view any models.
     */

    use SoftDeletes, HasRoles, HasFactory, Notifiable;
    public function viewAny(User $user)
    {
        return $user->hasRole(['super_admin'])|| $user->hasPermissionTo('User List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        return $user->hasRole(['super_admin'])||  $user->hasPermissionTo('User View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasRole(['super_admin'])||  $user->hasPermissionTo('User Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        return $user->hasRole(['super_admin'])||  $user->hasPermissionTo('User Edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        return $user->hasRole(['super_admin'])||  $user->hasPermissionTo('User Deactivate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        return $user->hasRole(['super_admin'])|| $user->hasPermissionTo('User Activate');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
    
}
