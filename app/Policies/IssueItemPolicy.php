<?php

namespace App\Policies;

use App\Models\IssueItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssueItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return  $user->can('IssueItems List');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IssueItem $issueItem): bool
    {
        return  $user->can('IssueItems View');
    }
public function edit(User $user, IssueItem $issueItem): bool

    {
        return  $user->can('IssueItems Edit');
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return  $user->can('IssueItems Create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IssueItem $issueItem): bool
    {
        return  $user->can('IssueItems Edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IssueItem $issueItem): bool
    {
          return $user->can('IssueItems Delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IssueItem $issueItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IssueItem $issueItem): bool
    {
        return false;
    }
}
