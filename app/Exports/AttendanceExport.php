<?php

namespace App\Exports;

use App\Models\EventAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        return EventAttendance::with(['member.department', 'member.position'])
            ->where('event_id', $this->eventId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 
            'Nama Lengkap', 
            'NIM', 
            'Departemen', 
            'Jabatan', 
            'Waktu Absen', 
            'Jenis Absen', 
            'Status', 
            'Keterangan'
        ];
    }

    public function map($attendance): array
    {
        static $no = 1;
        return [
            $no++,
            $attendance->member->full_name ?? '-',
            $attendance->member->nim ?? '-',
            $attendance->member->department->name ?? '-',
            $attendance->member->position->name ?? '-',
            $attendance->checked_in_at ? $attendance->checked_in_at->format('Y-m-d H:i:s') : '-',
            $attendance->is_self_checkin ? 'Mandiri' : 'Diinput Admin',
            $attendance->status,
            $attendance->note ?? '-'
        ];
    }
}
