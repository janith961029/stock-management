<?php

namespace App\Policies;

use App\Models\Csoapproval;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CsoapprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Csoapproval List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Csoapproval $csoapproval): bool
    {
        return  $user->can('Csoapproval View');
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->can('Csoapproval Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Csoapproval $csoapproval): bool
    {
        return   $user->can('Csoapproval Edit');
    }
 public function edit(User $user, Csoapproval $csoapproval): bool
    {
       return  $user->can('Csoapproval Edit');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Csoapproval $csoapproval): bool
    {
       return  $user->can('Csoapproval Delete');
    }

}
