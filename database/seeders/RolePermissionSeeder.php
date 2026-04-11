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
        $role = \App\Models\Role::firstOrCreate(['id' => \App\Models\Role::ADMIN], ['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
        $role->update(['name' => 'admin']); // Ensure name is correct if it already existed with different name
        
        // Employee role
        $role = \App\Models\Role::firstOrCreate(['id' => \App\Models\Role::EMPLOYEE], ['name' => 'employee']);
        $role->givePermissionTo(['view dashboard', 'manage students', 'manage events', 'manage employees']);
        // Don't force rename here if the user wanted it to be 'holo tech', 
        // but firstOrCreate with ID will keep the existing name if found.
        
        // Student role
        $role = \App\Models\Role::firstOrCreate(['id' => \App\Models\Role::STUDENT], ['name' => 'student']);
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
