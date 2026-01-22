<?php

namespace App\Policies;

use App\Models\ReciveItems;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecieveItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('RecieveItems List');
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReciveItems $ReciveItems): bool
    {
        return $user->can('RecieveItems View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function edit(User $user, ReciveItems $ReciveItems): bool
    {
        return $user->can('RecieveItems Edit');
    }

    public function create(User $user): bool
    {
       return $user->can('RecieveItems Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReciveItems $ReciveItems): bool
    {
        return $user->can('RecieveItems Update') || $user->can('RecieveItems Edit');
   }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReciveItems $ReciveItems): bool
    {
        return $user->can('RecieveItems Delete');
    }

    }
