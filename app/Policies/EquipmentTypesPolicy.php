<?php

namespace App\Policies;

use App\Models\EquipmentTypes;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EquipmentTypesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentTypes $equipmentTypes): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EquipmentTypes $equipmentTypes): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes Update');
    }
    public function edit(User $user, EquipmentTypes $equipmentTypes): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EquipmentTypes $equipmentTypes): bool
    {
        return $user->hasRole('super_admin') || $user->can('EquipmentTypes Delete');
    }

    
    
}
