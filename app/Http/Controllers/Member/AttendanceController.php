<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('member.profile.index')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Fetch all events that are not Draft, sorted by date
        $events = Event::where('status', '!=', 'Draft')
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        // Get this member's attendances mapped by event_id for easy lookup
        $attendances = EventAttendance::where('member_id', $member->id)
            ->get()
            ->keyBy('event_id');

        return view('member.attendance.index', compact('events', 'attendances'));
    }

    public function checkIn($eventId)
    {
        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->back()->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $event = Event::findOrFail($eventId);

        if (!$event->isAttendanceOpen()) {
            return redirect()->back()->with('error', 'Sesi absensi untuk acara ini tidak sedang dibuka.');
        }

        // Check if already checked in
        $attendance = EventAttendance::where('event_id', $eventId)
            ->where('member_id', $member->id)
            ->first();

        if ($attendance && $attendance->status === 'Hadir') {
            return redirect()->back()->with('info', 'Anda sudah melakukan check-in.');
        }

        EventAttendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'member_id' => $member->id,
            ],
            [
                'status' => 'Hadir',
                'checked_in_at' => now(),
                'is_self_checkin' => true,
                'note' => 'Check-in mandiri oleh anggota'
            ]
        );

        return redirect()->back()->with('success', 'Berhasil check-in absensi.');
    }

    public function submitStatus(Request $request, $eventId)
    {
        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->back()->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $event = Event::findOrFail($eventId);

        $request->validate([
            'status' => 'required|in:Izin,Sakit',
            'note' => 'required|string|max:500',
        ]);

        // Only allow status submission if they haven't already checked in
        $attendance = EventAttendance::where('event_id', $eventId)
            ->where('member_id', $member->id)
            ->first();

        if ($attendance && $attendance->status === 'Hadir') {
            return redirect()->back()->with('error', 'Anda tidak bisa mengubah status karena sudah melakukan check-in.');
        }

        EventAttendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'member_id' => $member->id,
            ],
            [
                'status' => $request->status,
                'note' => $request->note,
                'checked_in_at' => null,
                'is_self_checkin' => true,
            ]
        );

        return redirect()->back()->with('success', 'Status absensi berhasil diajukan.');
    }
}
