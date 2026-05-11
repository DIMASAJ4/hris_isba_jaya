@extends('layouts.admin')
@section('title', 'Detail Anggota')
@section('page-title', 'Detail Profil Anggota')

@section('content')
<div class="py-6 space-y-8 animate-fadeIn">
    
    {{-- TOP SECTION: GLASS CARD PROFILE --}}
    <div class="relative overflow-hidden bg-white rounded-3xl shadow-xl shadow-navy/5 border border-gray-100">
        {{-- Background Decor --}}
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-48 h-48 bg-amber/5 rounded-full blur-2xl"></div>

        <div class="relative p-8 md:p-10">
            <div class="flex flex-col md:flex-row gap-10 items-start">
                
                {{-- Left: Photo & Basic Info --}}
                <div class="w-full md:w-64 flex-shrink-0 text-center">
                    <div class="relative inline-block group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary to-blue-400 rounded-2xl rotate-3 scale-105 opacity-20 blur-sm"></div>
                        <div class="relative w-48 h-48 rounded-2xl overflow-hidden border-4 border-white shadow-2xl">
                            @if($member->photo)
                                <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-navy flex items-center justify-center text-white text-5xl font-bold">
                                    {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2">
                             <span class="badge-{{ strtolower($member->status) }} px-6 py-1.5 rounded-full shadow-lg border-2 border-white text-xs font-bold uppercase tracking-widest">
                                {{ $member->status }}
                             </span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-extrabold text-navy leading-tight">{{ $member->full_name }}</h3>
                        <p class="text-sm text-gray-400 mt-1 font-mono tracking-widest uppercase">NIM: {{ $member->nim }}</p>
                    </div>

                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('admin.members.edit', $member) }}" class="flex-1 btn-primary py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-primary/20">EDIT DATA</a>
                        <button onclick="confirmDelete('{{ $member->id }}')" class="flex-1 btn-danger py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-danger/20">HAPUS</button>
                    </div>
                </div>

                {{-- Right: Grid Information --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12">
                    
                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Departemen</p>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $member->department->name ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Jabatan</p>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $member->position->name ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Angkatan</p>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $member->batch_year ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Universitas</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->university ?? '-' }}</span>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">WhatsApp</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->phone ?? '-' }}</span>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Email Pribadi</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->email ?? '-' }}</span>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Tempat, Tgl Lahir</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->birth_place ?? '-' }}, {{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('d M Y') : '-' }}</span>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Jenis Kelamin</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->gender }}</span>
                    </div>

                    {{-- Item --}}
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Tgl Bergabung</p>
                        <span class="text-sm font-bold text-gray-700 block pl-1">{{ $member->joined_at ? \Carbon\Carbon::parse($member->joined_at)->format('d M Y') : '-' }}</span>
                    </div>

                    <div class="col-span-full">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-2">Alamat Lengkap</p>
                        <p class="text-sm text-gray-600 leading-relaxed pl-1">{{ $member->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MIDDLE SECTION: TABS --}}
    <div x-data="{ subtab: 'history' }" class="space-y-6">
        <div class="flex gap-1 bg-gray-200/50 p-1 rounded-2xl w-fit">
            <button @click="subtab = 'history'" :class="subtab === 'history' ? 'bg-white text-navy shadow-sm' : 'text-gray-500 hover:text-navy'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Riwayat Status</button>
            <button @click="subtab = 'account'" :class="subtab === 'account' ? 'bg-white text-navy shadow-sm' : 'text-gray-500 hover:text-navy'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Informasi Akun</button>
        </div>

        {{-- CONTENT: HISTORY --}}
        <div x-show="subtab === 'history'" class="card p-8 animate-fadeIn">
            <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                @forelse($member->statusLogs()->latest()->get() as $log)
                <div class="relative flex items-center justify-between md:justify-start">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-{{ $log->new_status == 'Aktif' ? 'success' : 'amber' }} shadow flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="ml-6 flex-1 bg-gray-50 p-4 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-extrabold text-navy">Status Diperbarui: {{ $log->new_status }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $log->note ?? 'Tidak ada catatan.' }}</p>
                            <p class="text-[10px] text-gray-400 mt-2 italic font-medium">Oleh: {{ $log->changedBy->name ?? 'Sistem' }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-xs font-bold text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-400 italic py-4">Belum ada riwayat perubahan status.</p>
                @endforelse
            </div>
        </div>

        {{-- CONTENT: ACCOUNT INFO --}}
        <div x-show="subtab === 'account'" x-cloak class="card p-8 animate-fadeIn">
             <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="w-24 h-24 rounded-3xl bg-navy/5 flex items-center justify-center text-navy">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09a13.916 13.916 0 004.611-7.394c.432-1.08.665-2.257.665-3.482V8a5.5 5.5 0 014.75-5.454M13.123 20h.077m0 0l2.183-5.238a2.114 2.114 0 00-.523-2.183l-5.238-2.183m5.238 2.183L20 13.123m-2.183-5.238l5.238-2.183a2.114 2.114 0 00.523-2.183l-5.238-2.183m-5.238 2.183L13.123 4"/></svg>
                </div>
                <div class="flex-1 space-y-4">
                    @if($member->user)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Username / Email Login</p>
                                <p class="text-lg font-bold text-navy">{{ $member->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Terakhir Login</p>
                                <p class="text-lg font-bold text-navy">{{ $member->user->last_login_at ? \Carbon\Carbon::parse($member->user->last_login_at)->format('d M Y, H:i') : 'Belum pernah login' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center md:text-left">
                            <p class="text-gray-500">Anggota ini belum memiliki akun akses sistem.</p>
                            <button class="btn-primary mt-4 py-2 px-6 rounded-xl text-xs font-bold">BUATKAN AKUN SEKARANG</button>
                        </div>
                    @endif
                </div>
             </div>
        </div>
    </div>

    {{-- BOTTOM SECTION: STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Stat Card --}}
        <div class="relative bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-navy/5 overflow-hidden group">
            <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">Masa Jabatan</p>
            <div class="flex items-baseline gap-2">
                @php
                    $diff = \Carbon\Carbon::parse($member->joined_at)->diff(now());
                    $text = $diff->y > 0 ? $diff->y . ' Tahun' : ($diff->m > 0 ? $diff->m . ' Bulan' : $diff->d . ' Hari');
                @endphp
                <h4 class="text-4xl font-black text-navy">{{ $text }}</h4>
            </div>
            <p class="text-xs text-gray-400 mt-2 font-medium">Sejak bergabung resmi</p>
        </div>

        {{-- Stat Card --}}
        <div class="relative bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-navy/5 overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-success"></div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">Kehadiran Rapat</p>
            <h4 class="text-4xl font-black text-navy">100%</h4>
            <p class="text-xs text-gray-400 mt-2 font-medium">Data kehadiran semester ini</p>
        </div>

        {{-- Stat Card --}}
        <div class="relative bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-navy/5 overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-navy"></div>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">Kode Anggota</p>
            <h4 class="text-3xl font-black text-success tracking-tighter">{{ $member->member_code }}</h4>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase">ID Unik Terverifikasi</p>
        </div>

    </div>

</div>

<style>
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .btn-primary {
        background: #2E86C1;
        color: white;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background: #2471A3;
        transform: translateY(-2px);
    }
    .btn-danger {
        background: #E74C3C;
        color: white;
        transition: all 0.3s;
    }
    .btn-danger:hover {
        background: #C0392B;
        transform: translateY(-2px);
    }
</style>
@endsection
