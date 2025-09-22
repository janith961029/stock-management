<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Establishment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstablishmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Establishment $establishment): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment View');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment Create');
    }

    
    public function update(User $user, Establishment $establishment): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment Update');
    }

  
    public function edit(User $user, Establishment $establishment): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment Edit');
    }

 
    public function delete(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Establishment Delete');
    }

    
}
