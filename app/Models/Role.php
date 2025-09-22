<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    /**
     * Many-to-Many relationship: Role -> Modules
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'role_module');
    }
}
