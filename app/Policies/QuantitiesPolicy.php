<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Quantities;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuantitiesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any quantities.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity List');
    }

    /**
     * Determine whether the user can view a quantity.
     */
    public function view(User $user,Quantities $quantities): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity View');
    }
    public function edit(User $user,Quantities $quantities): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity Edit');
    }
    /**
     * Determine whether the user can create quantities.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity Create');
    }

    /**
     * Determine whether the user can update a quantity.
     */
    public function update(User $user,Quantities $quantities): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity Update') || $user->can('Quantity Edit');
    }

    /**
     * Determine whether the user can delete a quantity.
     */
    public function delete(User $user,Quantities $quantities): bool
    {
        return $user->hasRole('super_admin') || $user->can('Quantity Delete');
    }


}
