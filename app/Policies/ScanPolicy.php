<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SerialNumbers;

class ScanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Scan List');
    }

    public function view(User $user, SerialNumbers $serial): bool
    {
        return $user->can('Scan View');
    }

    public function create(User $user): bool
    {
        return $user->can('Scan Create');
    }

    public function update(User $user, SerialNumbers $serial): bool
    {
        return $user->can('Scan Update') || $user->can('Scan Edit');
    }

    public function delete(User $user, SerialNumbers $serial): bool
    {
        return $user->can('Scan Delete');
    }
}
