<?php

namespace App\Policies;

use App\Models\IssuingType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuingTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
     return $user->hasRole('super_admin') || $user->can('IssuingType List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IssuingType $issuingType): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssuingType View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('IssuingType Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IssuingType $issuingType): bool
    {
       return $user->hasRole('super_admin') || $user->can('IssuingType Update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function edit(User $user, IssuingType $issuingType): bool
    {
             return $user->hasRole('super_admin') || $user->can('IssuingType Edit');

    }
    public function delete(User $user, IssuingType $issuingType): bool
    {
    return $user->hasRole('super_admin') || $user->can('IssuingType Delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
   
}
