<?php
namespace App\Policies;

use App\Models\ModelName;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModelNamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('ModelName List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModelName $modelName): bool
    {
        return $user->hasRole('super_admin') || $user->can('ModelName View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('ModelName Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModelName $modelName): bool
    {
        return $user->hasRole('super_admin') || $user->can('ModelName Update') || $user->can('ModelName Edit');
    }
    public function edit(User $user, ModelName $modelName): bool
    {
        return $user->hasRole('super_admin') || $user->can('ModelName Edit');
    }
    public function delete(User $user, ModelName $modelName): bool
    {
          return $user->hasRole('super_admin') || $user->can('ModelName Delete');
    }


}
