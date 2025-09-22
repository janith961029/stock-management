<?php

namespace App\Policies;

use App\Models\User;
use App\Models\titlenames;
use Illuminate\Auth\Access\Response;

class titlenamesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, titlenames $titlenames): bool
    {
         return $user->hasRole('super_admin') || $user->can('titlenames Update');
    }
public function edit(User $user, titlenames $titlenames): bool
    {
        return $user->hasRole('super_admin') || $user->can('titlenames Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, titlenames $titlenames): bool
    {
      return $user->hasRole('super_admin') || $user->can('titlenames Delete');
    }

}
