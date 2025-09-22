<?php

namespace App\Policies;

use App\Models\SignalUnit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SignalUnitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('SignalUnit List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SignalUnit $signalUnit): bool
    {
         return $user->hasRole('super_admin') || $user->can('SignalUnit View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('SignalUnit Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SignalUnit $signalUnit): bool
    {
      return $user->hasRole('super_admin') || $user->can('SignalUnit Update');
    }
    public function edit(User $user, SignalUnit $signalUnit): bool
    {
       return $user->hasRole('super_admin') || $user->can('SignalUnit Edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SignalUnit $signalUnit): bool
    {
       return $user->hasRole('super_admin') || $user->can('SignalUnit Delete');
    }

    
}
