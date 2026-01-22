<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;
    use HasRoles {
        hasPermissionTo as protected spatieHasPermissionTo;
    }

    protected $fillable = [
        'name',
        'password',
        'e_no',
        'username',
        'role_id',
        'service_no',
        'unit',
        'rank',
        'type',
        'nic',
        'signal_unit_id',
        'usr_status',
        'account_type',
        'eportal_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function signalUnit(): BelongsTo
    {
        return $this->belongsTo(SignalUnit::class, 'signal_unit_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (int) $this->usr_status === 1;
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if ($this->spatieHasPermissionTo($permission, $guardName)) {
            return true;
        }

        $permissionName = null;
        if (is_string($permission)) {
            $permissionName = $permission;
        } elseif (is_object($permission) && method_exists($permission, 'getName')) {
            $permissionName = $permission->getName();
        } elseif (is_object($permission) && property_exists($permission, 'name')) {
            $permissionName = $permission->name;
        }

        if ($permissionName !== null && str_ends_with($permissionName, ' Update')) {
            $editPermission = substr($permissionName, 0, -strlen(' Update')) . ' Edit';
            return $this->spatieHasPermissionTo($editPermission, $guardName);
        }

        return false;
    }
}
