<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Member::with(['department', 'position'])->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Anggota', 'Nama Lengkap', 'NIM', 'Gender', 'Departemen', 'Jabatan',
            'Status', 'Angkatan', 'Universitas', 'No HP', 'Email', 'Tanggal Bergabung'
        ];
    }

    public function map($member): array
    {
        static $no = 1;
        return [
            $no++,
            $member->member_code,
            $member->full_name,
            $member->nim,
            $member->gender,
            $member->department->name ?? '-',
            $member->position->name ?? '-',
            $member->status,
            $member->batch_year,
            $member->university,
            $member->phone,
            $member->email,
            $member->joined_at ? $member->joined_at->format('Y-m-d') : '-',
        ];
    }
}
