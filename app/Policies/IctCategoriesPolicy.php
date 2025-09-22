<?php

namespace App\Policies;

use App\Models\IctCategories;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IctCategoriesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->hasRole('super_admin') || $user->can('IctCategories List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IctCategories $ictCategories): bool
    {
        return $user->hasRole('super_admin') || $user->can('IctCategories View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('IctCategories Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IctCategories $ictCategories): bool
    {
         return $user->hasRole('super_admin') || $user->can('IctCategories Update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    
public function edit(User $user, IctCategories $ictCategories): bool
    {
        return $user->hasRole('super_admin') || $user->can('IctCategories Edit');
    }

    public function delete(User $user, IctCategories $ictCategories): bool
    {
        return $user->hasRole('super_admin') || $user->can('IctCategories Delete');
    }
    
}
