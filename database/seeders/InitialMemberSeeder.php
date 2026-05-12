<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialMemberSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Departemen
        $deptBph = Department::firstOrCreate(['slug' => 'bph'], [
            'name' => 'Badan Pengurus Harian',
            'description' => 'Inti kepengurusan ISBA JAYA',
            'icon' => 'briefcase',
            'color' => '#1E3A5F'
        ]);

        $deptSdm = Department::firstOrCreate(['slug' => 'sdm'], [
            'name' => 'Departemen SDM',
            'description' => 'Sumber Daya Mahasiswa',
            'icon' => 'users',
            'color' => '#8B5CF6'
        ]);

        $deptKominfo = Department::firstOrCreate(['slug' => 'kominfo'], [
            'name' => 'Departemen Kominfo',
            'description' => 'Komunikasi dan Informasi',
            'icon' => 'wifi',
            'color' => '#2E86C1'
        ]);

        $deptSeni = Department::firstOrCreate(['slug' => 'seni-olahraga'], [
            'name' => 'Departemen Seni & Olahraga',
            'description' => 'Minat, bakat, dan kesehatan',
            'icon' => 'palette',
            'color' => '#F39C12'
        ]);

        $deptAsrama = Department::firstOrCreate(['slug' => 'asrama'], [
            'name' => 'Departemen Asrama',
            'description' => 'Pengelolaan hunian mahasiswa',
            'icon' => 'home',
            'color' => '#27AE60'
        ]);

        // 2. Buat Jabatan Umum
        $posKetua = Position::firstOrCreate(['name' => 'Ketua Umum', 'department_id' => $deptBph->id], ['level' => 'Level 1 (Top)']);
        $posWakil = Position::firstOrCreate(['name' => 'Wakil Ketua Umum', 'department_id' => $deptBph->id], ['level' => 'Level 1 (Top)']);
        $posSekretaris = Position::firstOrCreate(['name' => 'Sekretaris Umum', 'department_id' => $deptBph->id], ['level' => 'Level 2 (Manajerial)']);
        $posBendahara = Position::firstOrCreate(['name' => 'Bendahara Umum', 'department_id' => $deptBph->id], ['level' => 'Level 2 (Manajerial)']);
        
        $posKadep = function($deptId, $name) {
            return Position::firstOrCreate(['name' => "Kepala $name", 'department_id' => $deptId], ['level' => 'Level 2 (Manajerial)']);
        };
        
        $posAnggota = function($deptId) {
            return Position::firstOrCreate(['name' => 'Anggota Departemen', 'department_id' => $deptId], ['level' => 'Level 4 (Pelaksana)']);
        };

        // 3. Masukkan Data Member
        $members = [
            // BPH
            ['name' => 'Rangga Pratama Yudha', 'uni' => 'Bina Sarana Informatika', 'addr' => 'Toboali, Bangka Selatan', 'dept' => $deptBph->id, 'pos' => $posKetua->id],
            ['name' => 'Vattrik Aldiansah', 'uni' => 'Institut Sains Teknologi Nasional', 'addr' => 'Terentang III, Bangka Tengah', 'dept' => $deptBph->id, 'pos' => $posWakil->id],
            ['name' => 'Fitria Firdawati', 'uni' => 'Universitas Tarumanagara', 'addr' => 'Sungailiat, Bangka', 'dept' => $deptBph->id, 'pos' => $posSekretaris->id],
            ['name' => 'Sabina Agustina', 'uni' => 'Institut ilmu Al-Quran Jakarta', 'addr' => 'Labu, Bangka', 'dept' => $deptBph->id, 'pos' => $posBendahara->id],

            // SDM
            ['name' => 'Ifa Purnamasari', 'uni' => 'Universitas Indraprasta PGRI', 'addr' => 'Simpang Perlang, Bangka Tengah', 'dept' => $deptSdm->id, 'pos' => $posKadep($deptSdm->id, 'Departemen SDM')->id],
            ['name' => 'Thariq Muhammad Abdul Aziz', 'uni' => 'PTIQ Jakarta University', 'addr' => 'Pemali, Bangka', 'dept' => $deptSdm->id, 'pos' => $posAnggota($deptSdm->id)->id],
            ['name' => 'Haikal Zulpikar', 'uni' => 'PTIQ Jakarta University', 'addr' => 'Jl. Raya Sungailiat Pagarawan 1, Bangka', 'dept' => $deptSdm->id, 'pos' => $posAnggota($deptSdm->id)->id],
            ['name' => 'Malisa', 'uni' => 'Institut Ilmu Alqur\'an', 'addr' => 'Simpang Perlang, Bangka Tengah', 'dept' => $deptSdm->id, 'pos' => $posAnggota($deptSdm->id)->id],
            ['name' => 'Lilis', 'uni' => 'Universitas Pancasila', 'addr' => 'Kepoh, Bangka Selatan', 'dept' => $deptSdm->id, 'pos' => $posAnggota($deptSdm->id)->id],
            ['name' => 'M Nur Fikri', 'uni' => 'PTIQ Jakarta University', 'addr' => 'Balunijuk, Bangka', 'dept' => $deptSdm->id, 'pos' => $posAnggota($deptSdm->id)->id],

            // KOMINFO
            ['name' => 'Bunga Aprilia Arianto', 'uni' => 'STTNF', 'addr' => 'Gabek, Pangkalpinang', 'dept' => $deptKominfo->id, 'pos' => $posKadep($deptKominfo->id, 'Departemen Kominfo')->id],
            ['name' => 'Aulya Muhammad Reza', 'uni' => 'Universitas Pancasila', 'addr' => 'Gabek, Pangkalpinang', 'dept' => $deptKominfo->id, 'pos' => $posAnggota($deptKominfo->id)->id],
            ['name' => 'Elmira Yaffa', 'uni' => 'Universitas Nasional', 'addr' => 'Kab.Belitung', 'dept' => $deptKominfo->id, 'pos' => $posAnggota($deptKominfo->id)->id],
            ['name' => 'Hendri Kuswantoro', 'uni' => 'Universitas Mercu Buana', 'addr' => 'Sungailiat, Bangka Induk', 'dept' => $deptKominfo->id, 'pos' => $posAnggota($deptKominfo->id)->id],
            ['name' => 'Miftahul Jannah', 'uni' => 'UHAMKA', 'addr' => 'Toboali, Bangka Selatan', 'dept' => $deptKominfo->id, 'pos' => $posAnggota($deptKominfo->id)->id],
            ['name' => 'Bella Sapitri', 'uni' => 'Universitas Indraprasta PGRI', 'addr' => 'Sungailiat, Bangka Induk', 'dept' => $deptKominfo->id, 'pos' => $posAnggota($deptKominfo->id)->id],

            // SENI & OLAHRAGA
            ['name' => 'M. Kodri Romadhan', 'uni' => 'Darunnajah Pusat', 'addr' => 'Balunijuk, Bangka Induk', 'dept' => $deptSeni->id, 'pos' => $posKadep($deptSeni->id, 'Departemen Seni & Olahraga')->id],
            ['name' => 'Zlatan Ibrahim', 'uni' => 'PTIQ Jakarta', 'addr' => 'Nibung, Bangka Tegah', 'dept' => $deptSeni->id, 'pos' => $posAnggota($deptSeni->id)->id],
            ['name' => 'Reyhan Alkheir', 'uni' => 'PTIQ Jakarta', 'addr' => 'Namang, Bangka Tengah', 'dept' => $deptSeni->id, 'pos' => $posAnggota($deptSeni->id)->id],
            ['name' => 'Dela Aulia', 'uni' => 'Binawan', 'addr' => 'Ranggas, Bangka Selatan', 'dept' => $deptSeni->id, 'pos' => $posAnggota($deptSeni->id)->id],
            ['name' => 'Afifah Imatullah', 'uni' => 'Binawan', 'addr' => 'Penyak, Bangka Tengah', 'dept' => $deptSeni->id, 'pos' => $posAnggota($deptSeni->id)->id],

            // ASRAMA
            ['name' => 'Ade gustari', 'uni' => 'UNINDRA, PGRI', 'addr' => 'sempan pemali Bangka', 'dept' => $deptAsrama->id, 'pos' => $posKadep($deptAsrama->id, 'Departemen Asrama')->id],
            ['name' => 'Chetrin Putria', 'uni' => 'Universitas Negeri Jakarta', 'addr' => 'Tanjung labu, lepar pongok', 'dept' => $deptAsrama->id, 'pos' => $posAnggota($deptAsrama->id)->id],
        ];

        $i = 1;
        foreach ($members as $m) {
            Member::updateOrCreate(
                ['full_name' => $m['name']],
                [
                    'university' => $m['uni'],
                    'address' => $m['addr'],
                    'department_id' => $m['dept'],
                    'position_id' => $m['pos'],
                    'status' => 'Aktif',
                    'joined_at' => now(),
                    'batch_year' => date('Y'),
                    'nim' => 'ISBA-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'member_code' => 'ISBA-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'gender' => 'Laki-laki', // Default
                ]
            );
            $i++;
        }
    }
}
