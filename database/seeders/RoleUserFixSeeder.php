<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleUserFixSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Roles Exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'chairman']);
        Role::firstOrCreate(['name' => 'member']);

        // Ensure Permissions Exist
        $viewProfile = Permission::firstOrCreate(['name' => 'view_own_profile']);
        Permission::firstOrCreate(['name' => 'edit_own_profile']);
        
        // Give permission to chairman
        Role::where('name', 'chairman')->first()->givePermissionTo($viewProfile);

        $password = Hash::make('admin123');

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@isbajaya.org'],
            ['name' => 'Admin HRD', 'password' => $password]
        );
        $admin->syncRoles(['admin']);

        // Chairman
        $ketua = User::updateOrCreate(
            ['email' => 'ketua@isbajaya.org'],
            ['name' => 'Ketua Umum', 'password' => $password]
        );
        $ketua->syncRoles(['chairman']);

        // Members
        $syarif = User::updateOrCreate(
            ['email' => 'syarif@isbajaya.org'],
            ['name' => 'Muhammad Syarif', 'password' => $password]
        );
        $syarif->syncRoles(['member']);

        $dimas = User::updateOrCreate(
            ['email' => 'dimas@isbajaya.org'],
            ['name' => 'Dimas', 'password' => $password]
        );
        $dimas->syncRoles(['member']);
    }
}
