<?php
namespace App\Policies;

use App\Models\StroopIssued;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StroopIssuedPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopIssued List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StroopIssued $stroopIssued): bool
    {
         return $user->hasRole('super_admin') || $user->can('StroopIssued View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopIssued Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StroopIssued $stroopIssued): bool
    {
          return $user->hasRole('super_admin') || $user->can('StroopIssued Update') || $user->can('StroopIssued Edit');
    }
public function edit(User $user, StroopIssued $stroopIssued): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopIssued Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StroopIssued $stroopIssued): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopIssued Delete');
    }


}
