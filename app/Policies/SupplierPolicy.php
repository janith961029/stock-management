<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any suppliers.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier List');
    }

    /**
     * Determine whether the user can view a supplier.
     */
    public function view(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier View');
    }
     public function edit(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier Edit');
    }
    /**
     * Determine whether the user can create suppliers.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier Create');
    }

    /**
     * Determine whether the user can update a supplier.
     */
    public function update(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier Update') || $user->can('Supplier Edit');
    }

    /**
     * Determine whether the user can delete a supplier.
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('super_admin') || $user->can('Supplier Delete');
    }


}
