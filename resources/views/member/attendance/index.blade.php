@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-bold text-2xl text-navy leading-tight">
        {{ __('Portal Absensi Anggota') }}
    </h2>
    <span class="px-3 py-1 bg-red-100 text-primary text-xs font-bold rounded-full border border-red-200">
        ABSENSI MANDIRI
    </span>
</div>
@endsection

@section('content')
<div class="py-12 bg-[#F4F6F9] min-h-screen" x-data="{ activePermitEventId: null, permitStatus: 'Izin', permitNote: '' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- SIDEBAR: PROFILE INFO CARD --}}
            <div class="space-y-6">
                @php
                    $member = auth()->user()->member;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-center p-8">
                    <div class="relative w-32 h-32 mx-auto mb-4">
                        @if($member && $member->photo)
                            <img src="{{ Storage::url($member->photo) }}" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                        @else
                            <div class="w-full h-full rounded-full bg-navy text-white flex items-center justify-center text-4xl font-bold border-4 border-white shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-navy">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-400">{{ $member->member_code ?? '-' }}</p>
                    
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">Angkatan {{ $member->batch_year ?? '-' }}</span>
                        <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-semibold rounded-lg">{{ $member->department->name ?? 'Umum' }}</span>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50 text-left space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Jabatan</p>
                            <p class="text-sm font-semibold text-navy mt-0.5">{{ $member->position->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">NIM</p>
                            <p class="text-sm font-semibold text-navy mt-0.5 font-mono">{{ $member->nim ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT AREA --}}
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <div class="border-b border-gray-50 pb-4">
                        <h4 class="text-lg font-bold text-navy">Kehadiran Acara Anda</h4>
                        <p class="text-xs text-gray-400">Daftar kehadiran Anda pada seluruh kegiatan resmi ISBA JAYA.</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($events as $event)
                            @php
                                $attendance = $attendances->get($event->id);
                            @endphp
                            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-[10px] font-bold text-primary bg-red-50 px-2 py-0.5 rounded">{{ $event->event_code }}</span>
                                        @if($event->isAttendanceOpen())
                                            <span class="px-2 py-0.5 text-[9px] font-bold bg-green-50 text-green-700 rounded border border-green-200 animate-pulse">Absen Dibuka</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[9px] font-bold bg-gray-50 text-gray-500 rounded border border-gray-100">Absen Ditutup</span>
                                        @endif
                                    </div>
                                    <h5 class="text-base font-bold text-navy">{{ $event->title }}</h5>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }} • {{ $event->location }}</p>
                                </div>

                                <div class="flex flex-col items-start md:items-end justify-center gap-2">
                                    @if($attendance && $attendance->status === 'Hadir')
                                        <div class="text-right">
                                            <span class="px-3 py-1 text-xs font-bold bg-green-50 text-green-700 rounded-full border border-green-200">Hadir</span>
                                            <p class="text-[9px] text-gray-400 mt-1 font-mono">Waktu: {{ $attendance->checked_in_at ? $attendance->checked_in_at->format('H:i') : '-' }}</p>
                                        </div>
                                    @elseif($attendance && ($attendance->status === 'Izin' || $attendance->status === 'Sakit'))
                                        <div class="text-right">
                                            <span class="px-3 py-1 text-xs font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-200">{{ $attendance->status }}</span>
                                            <p class="text-[9px] text-gray-400 mt-1 truncate max-w-[150px]" title="{{ $attendance->note }}">Ket: {{ $attendance->note }}</p>
                                        </div>
                                    @else
                                        @if($event->isAttendanceOpen())
                                            <div class="flex flex-wrap gap-2">
                                                <form action="{{ route('member.attendance.checkin', $event->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-green-600/10">
                                                        Check-In Hadir
                                                    </button>
                                                </form>
                                                <button type="button" @click="activePermitEventId = {{ $event->id }}; permitStatus = 'Izin'; permitNote = ''" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition-all">
                                                    Ajukan Izin/Sakit
                                                </button>
                                            </div>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold bg-red-50 text-red-700 rounded-full border border-red-100">Alpa (Tidak Hadir)</span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- MODAL PERMIT PER EVENT --}}
                            <div x-show="activePermitEventId === {{ $event->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fadeIn">
                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="activePermitEventId = null">
                                    <div class="bg-navy p-6 text-white">
                                        <h5 class="font-bold text-lg">Ajukan Izin atau Sakit</h5>
                                        <p class="text-blue-200 text-xs mt-1">Acara: {{ $event->title }}</p>
                                    </div>
                                    <form action="{{ route('member.attendance.permit', $event->id) }}" method="POST" class="p-6 space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Permohonan</label>
                                            <select name="status" x-model="permitStatus" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all text-sm">
                                                <option value="Izin">Izin</option>
                                                <option value="Sakit">Sakit</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alasan / Keterangan</label>
                                            <textarea name="note" rows="3" required x-model="permitNote" placeholder="Tuliskan keterangan rinci..." class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all text-sm"></textarea>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-4">
                                            <button type="button" @click="activePermitEventId = null" class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl text-xs font-bold hover:bg-gray-50 transition-all">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary/10">Kirim Pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 text-gray-400 italic bg-gray-50 rounded-2xl">
                                Belum ada kegiatan aktif yang didaftarkan.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .animate-fadeIn {
        animation: fadeIn 0.25s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endsection
