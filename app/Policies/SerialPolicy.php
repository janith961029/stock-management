<?php
namespace App\Policies;

use App\Models\Serial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SerialPolicy
{
    public function viewAny(User $user): bool
    {
         return $user->hasRole('super_admin') || $user->can('SerialNumber View');
    }

    public function view(User $user, Serial $serial): bool
    {
         return $user->hasRole('super_admin') || $user->can('SerialNumber List');
    }

    public function create(User $user): bool
    {
       return $user->hasRole('super_admin') || $user->can('SerialNumber Create');
    }
public function edit(User $user, Serial $serial): bool
{
    return $user->hasRole('super_admin') || $user->can('SerialNumber Edit');
}

    public function update(User $user, Serial $serial): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber Update') || $user->can('SerialNumber Edit');
    }

    public function delete(User $user, Serial $serial): bool
    {
        return $user->hasRole('super_admin') || $user->can('SerialNumber Delete');
    }

}
