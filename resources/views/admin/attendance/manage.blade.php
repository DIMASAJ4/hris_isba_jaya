@extends('layouts.admin')
@section('title', 'Kelola Absensi - ' . $event->title)
@section('page-title', 'Kelola Absensi')

@section('content')
<div class="py-6 space-y-6">

    {{-- HEADER CARD --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.attendance.index') }}" class="text-xs text-primary font-bold hover:underline mb-2 inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Absensi
            </a>
            <h1 class="text-xl font-bold text-navy">{{ $event->title }}</h1>
            <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $event->event_code }} • {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }} • {{ $event->location }}</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-600 font-bold">Sesi Absensi:</span>
            @if($event->attendance_open)
                <span class="px-2.5 py-1 text-xs font-bold bg-green-50 text-green-700 rounded-full border border-green-200">Dibuka</span>
            @else
                <span class="px-2.5 py-1 text-xs font-bold bg-gray-50 text-gray-500 rounded-full border border-gray-200">Ditutup</span>
            @endif
        </div>
    </div>

    {{-- QUICK STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Total Anggota</p>
            <p class="text-2xl font-black text-navy mt-1">{{ $attendances->count() }}</p>
        </div>
        <div class="bg-green-50/50 p-4 rounded-xl shadow-sm border border-green-100 text-center">
            <p class="text-[10px] font-black text-green-600 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-black text-green-700 mt-1">{{ $event->totalHadir() }}</p>
        </div>
        <div class="bg-blue-50/50 p-4 rounded-xl shadow-sm border border-blue-100 text-center">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $event->totalSakit() }}</p>
        </div>
        <div class="bg-amber-50/50 p-4 rounded-xl shadow-sm border border-amber-100 text-center">
            <p class="text-[10px] font-black text-amber-600 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $event->totalIzin() }}</p>
        </div>
        <div class="bg-red-50/50 p-4 rounded-xl shadow-sm border border-red-100 text-center">
            <p class="text-[10px] font-black text-red-600 uppercase tracking-wider">Alpa / Tidak Hadir</p>
            <p class="text-2xl font-black text-red-700 mt-1">{{ $event->totalTidakHadir() }}</p>
        </div>
    </div>

    {{-- ATTENDANCE TABLE FORM --}}
    <form action="{{ route('admin.attendance.updateBulk', $event->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card overflow-hidden">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Nama Anggota</th>
                            <th>NIM</th>
                            <th>Status Absensi</th>
                            <th>Keterangan / Catatan</th>
                            <th>Waktu & Sumber</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $index => $attendance)
                        <tr>
                            <td class="text-center text-gray-400">{{ $index + 1 }}</td>
                            <td>
                                <div>
                                    <p class="font-bold text-navy">{{ $attendance->member->full_name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $attendance->member->department->name ?? '-' }} • {{ $attendance->member->position->name ?? '-' }}</p>
                                </div>
                            </td>
                            <td><span class="font-mono text-xs">{{ $attendance->member->nim }}</span></td>
                            <td>
                                <select name="attendances[{{ $attendance->id }}][status]" class="form-select text-xs w-40">
                                    <option value="Tidak Hadir" {{ $attendance->status === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir (Alpa)</option>
                                    <option value="Hadir" {{ $attendance->status === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Izin" {{ $attendance->status === 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Sakit" {{ $attendance->status === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="attendances[{{ $attendance->id }}][note]" value="{{ $attendance->note }}" placeholder="Alasan izin/sakit, dll..." class="form-input text-xs w-full max-w-xs">
                            </td>
                            <td>
                                @if($attendance->checked_in_at)
                                    <div class="text-xs">
                                        <p class="font-semibold text-navy">{{ $attendance->checked_in_at->format('H:i') }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $attendance->is_self_checkin ? 'Mandiri (Portal)' : 'Diinput Admin' }}</p>
                                    </div>
                                @elseif($attendance->status === 'Izin' || $attendance->status === 'Sakit')
                                    <div class="text-xs">
                                        <p class="font-semibold text-amber-700">{{ $attendance->status }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $attendance->is_self_checkin ? 'Diajukan Mandiri' : 'Diinput Admin' }}</p>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SAVE ACTION --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.attendance.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Perubahan Absensi
            </button>
        </div>
    </form>

</div>
@endsection
