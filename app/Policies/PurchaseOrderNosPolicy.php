<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseOrderNos;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderNosPolicy
{
   use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseOrderNos $purchaseOrderNos): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos Create');
    }
  public function edit(User $user, PurchaseOrderNos $purchaseOrderNos): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos Edit');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseOrderNos $purchaseOrderNos): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos Update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseOrderNos $purchaseOrderNos): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('PurchaseOrderNos Delete');
    }


 
}
