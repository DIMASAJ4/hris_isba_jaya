@extends('layouts.admin')
@section('title', 'Kelola Absensi')
@section('page-title', 'Kelola Absensi Acara')

@section('content')
<div class="py-6 space-y-6">

    {{-- SEARCH & INFO --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.attendance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Cari Acara</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama acara..." class="form-input pl-10">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full justify-center">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card overflow-hidden">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Acara</th>
                        <th>Tanggal</th>
                        <th class="text-center">Sesi Absen</th>
                        <th class="text-center">Rekap (H/S/I/A)</th>
                        <th class="text-center w-56">Kelola Sesi</th>
                        <th class="text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td class="text-center text-gray-400">{{ ($events->currentPage()-1) * $events->perPage() + $loop->iteration }}</td>
                        <td><span class="font-mono text-xs font-bold text-primary bg-red-50 px-2 py-1 rounded">{{ $event->event_code }}</span></td>
                        <td><p class="font-bold text-navy truncate max-w-[200px]">{{ $event->title }}</p></td>
                        <td><span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</span></td>
                        <td class="text-center">
                            @if($event->attendance_open)
                                <span class="px-2.5 py-1 text-xs font-bold bg-green-50 text-green-700 rounded-full border border-green-200">Dibuka</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold bg-gray-50 text-gray-500 rounded-full border border-gray-200">Ditutup</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1.5 font-bold text-xs">
                                <span class="px-1.5 py-0.5 bg-green-100 text-green-800 rounded">{{ $event->totalHadir() }}</span>
                                <span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded">{{ $event->totalSakit() }}</span>
                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 rounded">{{ $event->totalIzin() }}</span>
                                <span class="px-1.5 py-0.5 bg-red-100 text-red-800 rounded">{{ $event->totalTidakHadir() }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.attendance.toggle', $event->id) }}" method="POST" class="inline">
                                @csrf
                                @if($event->attendance_open)
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Tutup Absensi
                                    </button>
                                @else
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Buka Absensi
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.attendance.manage', $event->id) }}" class="p-2 text-primary hover:bg-red-50 rounded-lg transition-all" title="Kelola Absensi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                </a>
                                <a href="{{ route('admin.attendance.excel', $event->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Export Excel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                                <a href="{{ route('admin.attendance.pdf', $event->id) }}" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Export PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-20 text-gray-400 italic">Belum ada data acara.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $events->links() }}
    </div>

</div>
@endsection
