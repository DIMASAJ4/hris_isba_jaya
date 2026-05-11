<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'SDM',
                'slug' => 'sdm',
                'description' => 'Sumber Daya Mahasiswa',
                'icon' => 'users',
                'color' => '#8B5CF6',
                'positions' => [
                    ['name' => 'Kepala Departemen SDM', 'level' => 'Level 2 (Manajerial)'],
                    ['name' => 'Sekretaris Departemen', 'level' => 'Level 3 (Staf Ahli)'],
                    ['name' => 'Staf Rekrutmen', 'level' => 'Level 4 (Pelaksana)'],
                    ['name' => 'Staf Kesejahteraan Anggota', 'level' => 'Level 4 (Pelaksana)'],
                ]
            ],
            [
                'name' => 'Kominfo',
                'slug' => 'kominfo',
                'description' => 'Komunikasi & Informasi',
                'icon' => 'wifi',
                'color' => '#2E86C1',
                'positions' => [
                    ['name' => 'Kepala Departemen Kominfo', 'level' => 'Level 2 (Manajerial)'],
                    ['name' => 'Admin Media Sosial', 'level' => 'Level 3 (Staf Ahli)'],
                    ['name' => 'Staf Dokumentasi', 'level' => 'Level 4 (Pelaksana)'],
                ]
            ],
            [
                'name' => 'Seni',
                'slug' => 'seni',
                'description' => 'Seni & Kreativitas',
                'icon' => 'palette',
                'color' => '#F39C12',
                'positions' => [
                    ['name' => 'Kepala Departemen Seni', 'level' => 'Level 2 (Manajerial)'],
                    ['name' => 'Koordinator Acara', 'level' => 'Level 3 (Staf Ahli)'],
                    ['name' => 'Staf Kreatif', 'level' => 'Level 4 (Pelaksana)'],
                ]
            ],
            [
                'name' => 'Keagamaan',
                'slug' => 'keagamaan',
                'description' => 'Kerohanian & Ibadah',
                'icon' => 'star',
                'color' => '#27AE60',
                'positions' => [
                    ['name' => 'Kepala Departemen Keagamaan', 'level' => 'Level 2 (Manajerial)'],
                    ['name' => 'Koordinator Kajian', 'level' => 'Level 3 (Staf Ahli)'],
                    ['name' => 'Staf Ibadah', 'level' => 'Level 4 (Pelaksana)'],
                ]
            ],
        ];

        foreach ($departments as $deptData) {
            $positions = $deptData['positions'];
            unset($deptData['positions']);

            $department = Department::updateOrCreate(['slug' => $deptData['slug']], $deptData);

            foreach ($positions as $pos) {
                Position::updateOrCreate(
                    ['department_id' => $department->id, 'name' => $pos['name']],
                    $pos
                );
            }
        }
    }
}
