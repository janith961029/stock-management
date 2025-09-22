<?php

namespace App\Policies;

use App\Models\IssuePlaces;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuePlacesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssuePlaces List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IssuePlaces $issuePlaces): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssuePlaces View');
    }
    

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssuePlaces Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IssuePlaces $issuePlaces): bool
    {
          return $user->hasRole('super_admin') || $user->can('IssuePlaces Update');
    }
    public function edit(User $user, IssuePlaces $issuePlaces): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssuePlaces Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IssuePlaces $issuePlaces): bool
    {
         return $user->hasRole('super_admin') || $user->can('IssuePlaces Delete');
    }

   
}
