<?php

namespace App\Policies;

use App\Models\User;
use App\Models\serial_numbers;
use Illuminate\Auth\Access\HandlesAuthorization;

class SerialNumberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view a serial number.
     */
    public function view(User $user, serial_numbers $serialNumber): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber View');
    }
 public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber List');
    }
public function edit(User $user, serial_numbers $serialNumber): bool
{
    return $user->hasRole('super_admin') || $user->can('SerialNumber Edit');
}
    /**
     * Determine whether the user can create serial numbers.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber Create');
    }

    /**
     * Determine whether the user can update a serial number.
     */
    public function update(User $user, serial_numbers $serialNumber): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber Update');
    }

    /**
     * Determine whether the user can delete a serial number.
     */
    public function delete(User $user, serial_numbers $serialNumber): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber Delete');
    }
}
