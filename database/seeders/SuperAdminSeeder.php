<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Create role if not exists
        $role = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        // Create super admin user if not exists
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('NewSecurePassword123!'),
            ]
        );

        // Assign role to user if not assigned
        if (!$user->hasRole('super_admin')) {
            $user->assignRole($role);
        }

        // Assign all existing permissions (optional)
        $permissions = Permission::all();
        if ($permissions->count() > 0) {
            $user->givePermissionTo($permissions);
        }

        $this->command->info('Super Admin account created / updated successfully.');
    }
}
