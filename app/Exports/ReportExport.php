<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Member::with(['department', 'position']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        if (!empty($this->filters['batch_year'])) {
            $query->where('batch_year', $this->filters['batch_year']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Anggota', 'Nama Lengkap', 'NIM', 'Status', 'Departemen', 'Jabatan'
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
            $member->status,
            $member->department->name ?? '-',
            $member->position->name ?? '-',
        ];
    }
}
