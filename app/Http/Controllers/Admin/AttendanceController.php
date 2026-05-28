<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Member;
use App\Models\EventAttendance;
use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('attendances');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%");
        }

        $events = $query->latest('event_date')->paginate(10);

        return view('admin.attendance.index', compact('events'));
    }

    public function manage($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Sync active members to make sure everyone has an attendance record for this event
        $activeMembers = Member::where('status', 'Aktif')->get();
        foreach ($activeMembers as $member) {
            EventAttendance::firstOrCreate([
                'event_id' => $event->id,
                'member_id' => $member->id,
            ], [
                'status' => 'Tidak Hadir',
                'is_self_checkin' => false,
            ]);
        }

        $attendances = EventAttendance::with(['member.department', 'member.position'])
            ->where('event_id', $eventId)
            ->get();

        return view('admin.attendance.manage', compact('event', 'attendances'));
    }

    public function toggleAttendance($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        if ($event->attendance_open) {
            $event->update([
                'attendance_open' => false,
                'attendance_closed_at' => now(),
            ]);
            $message = 'Sesi absensi berhasil ditutup.';
        } else {
            $event->update([
                'attendance_open' => true,
                'attendance_opened_at' => now(),
                'attendance_closed_at' => null,
            ]);
            $message = 'Sesi absensi berhasil dibuka.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function updateBulk(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Tidak Hadir,Izin,Sakit',
            'attendances.*.note' => 'nullable|string',
        ]);

        foreach ($request->attendances as $attendanceId => $data) {
            $attendance = EventAttendance::where('event_id', $eventId)->findOrFail($attendanceId);
            
            $updateData = [
                'status' => $data['status'],
                'note' => $data['note'] ?? null,
            ];

            // If changing status to Hadir and checked_in_at was null, set it
            if ($data['status'] === 'Hadir' && !$attendance->checked_in_at) {
                $updateData['checked_in_at'] = now();
            } elseif ($data['status'] !== 'Hadir') {
                $updateData['checked_in_at'] = null;
            }

            $attendance->update($updateData);
        }

        return redirect()->route('admin.attendance.manage', $eventId)->with('success', 'Absensi berhasil diperbarui.');
    }

    public function exportPdf($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $attendances = EventAttendance::with(['member.department', 'member.position'])
            ->where('event_id', $eventId)
            ->get();

        $pdf = Pdf::loadView('admin.attendance.pdf', compact('event', 'attendances'));
        
        $filename = 'rekap-absen-' . str_replace('/', '-', $event->event_code) . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel($eventId)
    {
        $event = Event::findOrFail($eventId);
        $filename = 'rekap-absen-' . str_replace('/', '-', $event->event_code) . '.xlsx';
        return Excel::download(new AttendanceExport($eventId), $filename);
    }
}
