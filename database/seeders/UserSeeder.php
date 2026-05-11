<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@isbajaya.org'],
            [
                'name' => 'Admin HRD',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole('admin');

        $chairman = User::updateOrCreate(
            ['email' => 'ketua@isbajaya.org'],
            [
                'name' => 'Ketua Umum',
                'password' => Hash::make('ketua123'),
            ]
        );
        $chairman->assignRole('chairman');

        $member = User::updateOrCreate(
            ['email' => 'syarif@isbajaya.org'],
            [
                'name' => 'Muhammad Syarif',
                'password' => Hash::make('member123'),
            ]
        );
        $member->assignRole('member');
    }
}
