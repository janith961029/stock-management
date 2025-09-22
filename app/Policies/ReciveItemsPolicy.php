<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReciveItems;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReciveItemsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list of ReciveItems.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('RecieveItems List');
    }

    /**
     * Determine whether the user can view a specific ReciveItem.
     */
    public function view(User $user, ReciveItems $reciveItem): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssueItems View');
    }
public function edit(User $user, ReciveItems $reciveItem): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssueItems Edit');
    }

    /**
     * Determine whether the user can create ReciveItems.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssueItems Create');
    }

    /**
     * Determine whether the user can update a ReciveItem.
     */
    public function update(User $user, ReciveItems $reciveItem): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssueItems Update');
    }

    /**
     * Determine whether the user can delete a ReciveItem.
     */
    public function delete(User $user, ReciveItems $reciveItem): bool
    {
        return $user->hasRole('super_admin') || $user->can('IssueItems Delete');
    }
    


}
