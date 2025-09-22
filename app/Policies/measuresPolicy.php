<?php

namespace App\Policies;

use App\Models\User;
use App\Models\measures;
use Illuminate\Auth\Access\Response;

class measuresPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('measures List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, measures $measures): bool
    {
        return $user->hasRole('super_admin') || $user->can('measures View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasRole('super_admin') || $user->can('measures Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, measures $measures): bool
    {
         return $user->hasRole('super_admin') || $user->can('measures Update');
    }
  public function edit(User $user, measures $measures): bool
    {
        return $user->hasRole('super_admin') || $user->can('measures Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, measures $measures): bool
    {
    return $user->hasRole('super_admin') || $user->can('measures Delete');
    }

   
   
}
