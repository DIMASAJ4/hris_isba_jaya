<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Member;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $syarifUser = User::where('email', 'syarif@isbajaya.org')->first();
        $sdmDept = Department::where('slug', 'sdm')->first();
        $staffPos = Position::where('name', 'Staf Rekrutmen')->first();

        $names = [
            'Muhammad Syarif', 'Budi Santoso', 'Siti Aminah', 'Dewi Lestari', 
            'Agus Wijaya', 'Rina Permata', 'Eko Prasetyo', 'Lusi Indah', 
            'Rahmat Hidayat', 'Linda Sari'
        ];

        $universities = ['Universitas Indonesia', 'UPN Veteran', 'Gunadarma', 'BINUS', 'Universitas Gadjah Mada'];
        $statuses = ['Aktif', 'Aktif', 'Aktif', 'Aktif', 'Aktif', 'Aktif', 'Tidak Aktif', 'Tidak Aktif', 'Alumni', 'Pending'];
        $years = ['2021', '2022', '2023'];

        foreach ($names as $index => $name) {
            $isSyarif = ($name === 'Muhammad Syarif');
            
            Member::create([
                'user_id' => $isSyarif ? $syarifUser->id : null,
                'full_name' => $name,
                'nim' => '12010' . ($index + 100),
                'gender' => ($index % 2 == 0) ? 'Laki-laki' : 'Perempuan',
                'birth_place' => 'Jakarta',
                'birth_date' => '2002-05-15',
                'phone' => '0812345678' . $index,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'address' => 'Jl. Merdeka No. ' . ($index + 1),
                'department_id' => $sdmDept->id,
                'position_id' => $staffPos->id,
                'batch_year' => $years[array_rand($years)],
                'university' => $universities[array_rand($universities)],
                'status' => $statuses[$index],
                'joined_at' => '2023-01-10',
            ]);
        }
    }
}
