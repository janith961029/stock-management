<?php

namespace App\Policies;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Scan List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Scan $scan): bool
    {
         return $user->hasRole('super_admin') || $user->can('Scan View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Scan Create');
    }
   public function edit(User $user,  Scan $scan): bool
    {
        return $user->hasRole('super_admin') || $user->can('Scan Edit');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Scan $scan): bool
    {
        return $user->hasRole('super_admin') || $user->can('Scan Update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Scan $scan): bool
    {
       return $user->hasRole('super_admin') || $user->can('Scan Delete');
    }

   
}
