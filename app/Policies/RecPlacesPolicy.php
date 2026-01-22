<?php
namespace App\Policies;

use App\Models\RecPlaces;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecPlacesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('RecPlaces List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RecPlaces $recPlaces): bool
    {
         return $user->hasRole('super_admin') || $user->can('RecPlaces View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('RecPlaces Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecPlaces $recPlaces): bool
    {
        return $user->hasRole('super_admin') || $user->can('RecPlaces Update') || $user->can('RecPlaces Edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function edit(User $user, RecPlaces $recPlaces): bool
    {
          return $user->hasRole('super_admin') || $user->can('RecPlaces Edit');
    }
    public function delete(User $user, RecPlaces $recPlaces): bool
    {
          return $user->hasRole('super_admin') || $user->can('RecPlaces Delete');
    }
    /**
     * Determine whether the user can restore the model.
     */

}
