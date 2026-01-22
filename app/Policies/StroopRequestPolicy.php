<?php
namespace App\Policies;

use App\Models\StroopRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StroopRequestPolicy
{


    public function viewAny(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('StroopRequest List');
    }


    public function view(User $user, StroopRequest $stroopRequest): bool
    {
         return $user->hasRole('super_admin') || $user->can('StroopRequest View');
    }


    public function create(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('StroopRequest Create');
    }


    public function update(User $user, StroopRequest $stroopRequest): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopRequest Update') || $user->can('StroopRequest Edit');
    }
   public function edit(User $user, StroopRequest $stroopRequest): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopRequest Edit');
    }


    public function delete(User $user, StroopRequest $stroopRequest): bool
    {
        return $user->hasRole('super_admin') || $user->can('StroopRequest Delete');
    }
}
