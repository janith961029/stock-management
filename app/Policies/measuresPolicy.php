<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Measures;
use Illuminate\Auth\Access\Response;

class MeasuresPolicy
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
    public function view(User $user, Measures $measures): bool
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
    public function update(User $user, Measures $measures): bool
    {
         return $user->hasRole('super_admin') || $user->can('measures Update') || $user->can('measures Edit');
    }
  public function edit(User $user, Measures $measures): bool
    {
        return $user->hasRole('super_admin') || $user->can('measures Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Measures $measures): bool
    {
    return $user->hasRole('super_admin') || $user->can('measures Delete');
    }



}
