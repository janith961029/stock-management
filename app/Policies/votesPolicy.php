<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Votes;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any votes.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes List');
    }

    /**
     * Determine whether the user can view a vote.
     */
    public function view(User $user, Votes $votes): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes View');
    }

    /**
     * Determine whether the user can create votes.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes Create');
    }

    public function edit(User $user, Votes $votes): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes Edit');
    }


    /**
     * Determine whether the user can update a vote.
     */
    public function update(User $user, Votes $votes): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes Update') || $user->can('Votes Edit') || $user->can('Votes Edit');
    }

    /**
     * Determine whether the user can delete a vote.
     */
    public function delete(User $user, Votes $votes): bool
    {
        return $user->hasRole('super_admin') || $user->can('Votes Delete');
    }

}
