<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role admin ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $password = Hash::make('admin123');

        $users = [
            [
                'name' => 'Bunga Aprilia Arianto',
                'email' => 'bunga@isbajaya.org',
                'password' => $password,
            ],
            [
                'name' => 'Aulya Muhammad Reza',
                'email' => 'aulya@isbajaya.org',
                'password' => $password,
            ],
            [
                'name' => 'Hendri Kuswantoro',
                'email' => 'hendri@isbajaya.org',
                'password' => $password,
            ],
            [
                'name' => 'Miftahul Jannah',
                'email' => 'miftah@isbajaya.org',
                'password' => $password,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );
            $user->syncRoles(['admin']);
        }
    }
}
