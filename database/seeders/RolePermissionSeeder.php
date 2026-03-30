<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::firstOrCreate(['name' => 'manage events']);
        Permission::firstOrCreate(['name' => 'manage students']);
        Permission::firstOrCreate(['name' => 'manage employees']);
        Permission::firstOrCreate(['name' => 'view dashboard']);
        // Aggregate permissions
        Permission::firstOrCreate(['name' => 'manage announcements']);
        Permission::firstOrCreate(['name' => 'manage speakers']);

        // Create roles and assign created permissions

        // Admin role
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // Employee role
        $role = Role::firstOrCreate(['name' => 'employee']);
        $role->givePermissionTo(['view dashboard', 'manage students', 'manage events', 'manage employees']);

        // Student role
        $role = Role::firstOrCreate(['name' => 'student']);
        $role->givePermissionTo(['view dashboard']);

        // Assign Admin role to existing users if they exist
        $adminEmails = ['anthonybiel.dev@gmail.com', 'test@example.com'];
        foreach ($adminEmails as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $user->assignRole('admin');
            }
        }
    }
}
