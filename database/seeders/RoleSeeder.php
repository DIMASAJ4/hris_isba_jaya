<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view-dashboard', 'view-members', 'create-members', 'edit-members', 'delete-members',
            'import-members', 'export-members', 'verify-status', 'manage-departments',
            'manage-positions', 'manage-users', 'manage-roles', 'view-reports', 'export-reports',
            'view-own-profile', 'edit-own-profile'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role: admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Role: chairman
        $chairman = Role::firstOrCreate(['name' => 'chairman']);
        $chairman->syncPermissions([
            'view-dashboard', 'view-members', 'view-reports', 'export-reports', 'verify-status', 'export-members'
        ]);

        // Role: member
        $member = Role::firstOrCreate(['name' => 'member']);
        $member->syncPermissions(['view-own-profile', 'edit-own-profile']);
    }
}
