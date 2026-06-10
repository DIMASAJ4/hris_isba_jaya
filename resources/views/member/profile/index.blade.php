@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-bold text-2xl text-navy leading-tight">
        {{ __('Portal Anggota') }}
    </h2>
    <span class="px-4 py-1.5 text-xs font-black rounded-full uppercase tracking-wider
        {{ $member->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-amber-50 text-amber-600 border border-amber-200' }}">
        {{ strtoupper($member->status ?? 'MEMBER') }}
    </span>
</div>
@endsection

@section('content')
<div class="py-8 bg-gradient-to-br from-[#F4F6F9] to-[#EAEEF3] min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm animate-fadeIn">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div x-data="{ tab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- ============================================ --}}
            {{-- SIDEBAR: PROFILE CARD --}}
            {{-- ============================================ --}}
            <div class="space-y-6">
                {{-- AVATAR CARD --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Banner Top --}}
                    <div class="h-28 bg-gradient-to-br from-[#980D0D] to-[#1E3A5F] relative">
                        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=40 height=40 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M0 40L40 0H20L0 20zM40 40V20L20 40z%22 fill=%22%23fff%22 fill-opacity=0.3/%3E%3C/svg%3E');"></div>
                    </div>
                    
                    <div class="text-center px-6 pb-6 -mt-14">
                        {{-- Avatar --}}
                        <div class="relative w-28 h-28 mx-auto mb-4">
                            @if($member && $member->photo)
                                <img src="{{ Storage::url($member->photo) }}" class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg ring-2 ring-gray-100">
                            @else
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-[#980D0D] to-[#c41e1e] text-white flex items-center justify-center text-4xl font-black border-4 border-white shadow-lg ring-2 ring-gray-100">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-[3px] border-white rounded-full shadow-sm"></div>
                        </div>
                        
                        <h3 class="text-xl font-black text-[#1E3A5F]">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-400 font-mono mt-1">{{ $member->member_code ?? 'ISBA-NEW' }}</p>
                        
                        {{-- Badges --}}
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-50 text-gray-600 text-[11px] font-bold rounded-lg border border-gray-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Angkatan {{ $member->batch_year ?? '-' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-[#980D0D] text-[11px] font-bold rounded-lg border border-rose-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                {{ $member->department->name ?? 'Umum' }}
                            </span>
                            @if($member->position)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-[#1E3A5F] text-[11px] font-bold rounded-lg border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                {{ $member->position->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- NAVIGATION TABS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
                    <nav class="space-y-1">
                        <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-gradient-to-r from-[#1E3A5F] to-[#2a5080] text-white shadow-md shadow-[#1E3A5F]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-[#1E3A5F]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-sm font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Detail Profil
                            <svg x-show="tab === 'profile'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        </button>
                        <button @click="tab = 'edit'" :class="tab === 'edit' ? 'bg-gradient-to-r from-[#1E3A5F] to-[#2a5080] text-white shadow-md shadow-[#1E3A5F]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-[#1E3A5F]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-sm font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profil
                            <svg x-show="tab === 'edit'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        </button>
                        <button @click="tab = 'structure'" :class="tab === 'structure' ? 'bg-gradient-to-r from-[#1E3A5F] to-[#2a5080] text-white shadow-md shadow-[#1E3A5F]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-[#1E3A5F]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-sm font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Struktur Organisasi
                            <svg x-show="tab === 'structure'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        </button>
                    </nav>
                </div>

                {{-- QUICK STATS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Statistik Saya</h4>
                    <div class="space-y-4">
                        @php
                            $totalEvents = \App\Models\Event::count();
                            $myAttendance = $member->attendances()->where('status', 'Hadir')->count();
                            $attendPercent = $totalEvents > 0 ? round(($myAttendance / $totalEvents) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-gray-500 font-medium">Kehadiran</span>
                                <span class="font-black text-[#1E3A5F]">{{ $myAttendance }}/{{ $totalEvents }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-full rounded-full transition-all duration-700" style="width: {{ $attendPercent }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                            <span class="text-xs text-gray-400">Bergabung sejak</span>
                            <span class="text-xs font-bold text-[#1E3A5F]">{{ $member->joined_at ? $member->joined_at->format('d M Y') : ($member->created_at ? $member->created_at->format('d M Y') : '-') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- MAIN CONTENT AREA --}}
            {{-- ============================================ --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- ========== TAB: DETAIL PROFILE ========== --}}
                <div x-show="tab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    {{-- Personal Info --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-gray-50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#1E3A5F]/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#1E3A5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E3A5F]">Informasi Pribadi</h4>
                                <p class="text-[11px] text-gray-400">Data lengkap keanggotaan Anda di sistem HRIS.</p>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        Nama Lengkap
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->full_name ?? Auth::user()->name }}</p>
                                </div>
                                {{-- NIM --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                        NIM / Identitas
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm font-mono">{{ $member->nim ?? '-' }}</p>
                                </div>
                                {{-- Email --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Email
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ Auth::user()->email }}</p>
                                </div>
                                {{-- Telepon --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        Nomor WhatsApp
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->phone ?? '-' }}</p>
                                </div>
                                {{-- Universitas --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9-5 9 5-9 5z"/></svg>
                                        Universitas
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->university ?? '-' }}</p>
                                </div>
                                {{-- Departemen --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                        Departemen
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->department->name ?? '-' }}</p>
                                </div>
                                {{-- Jabatan --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Jabatan
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->position->name ?? '-' }}</p>
                                </div>
                                {{-- Gender --}}
                                <div class="group p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                        Jenis Kelamin
                                    </p>
                                    <p class="text-gray-800 font-bold text-sm">{{ $member->gender ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="mt-6 p-4 rounded-xl border border-gray-50 hover:border-[#980D0D]/20 hover:bg-rose-50/30 transition-all">
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Alamat Tinggal
                                </p>
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $member->address ?? 'Belum melengkapi data alamat.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== TAB: EDIT PROFILE ========== --}}
                <div x-show="tab === 'edit'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-gray-50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E3A5F]">Perbarui Profil</h4>
                                <p class="text-[11px] text-gray-400">Update data diri Anda agar tetap akurat dan valid.</p>
                            </div>
                        </div>

                        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                                    <input type="text" name="phone" value="{{ $member->phone }}" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-[#980D0D] focus:ring-[#980D0D]/20 transition-all py-3 px-4 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-2">Universitas</label>
                                    <input type="text" name="university" value="{{ $member->university }}" placeholder="Nama Universitas" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-[#980D0D] focus:ring-[#980D0D]/20 transition-all py-3 px-4 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap Anda..." class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-[#980D0D] focus:ring-[#980D0D]/20 transition-all py-3 px-4 text-sm">{{ $member->address }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-2">Foto Profil</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border-2 border-dashed border-gray-200">
                                        @if($member && $member->photo)
                                            <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="photo" accept="image/jpeg,image/png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-[#980D0D] hover:file:bg-rose-100 transition-all cursor-pointer">
                                        <p class="text-[10px] text-gray-400 mt-1.5">Format: JPG, PNG. Maksimal 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-50 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#980D0D] to-[#c41e1e] hover:from-[#7a0a0a] hover:to-[#a51919] text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-[#980D0D]/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ========== TAB: STRUKTUR ORGANISASI ========== --}}
                <div x-show="tab === 'structure'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-br from-[#1E3A5F] to-[#2a5080] rounded-2xl p-6 text-white shadow-lg shadow-[#1E3A5F]/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black">Struktur Organisasi</h4>
                                <p class="text-blue-200 text-sm">Departemen Anda: <span class="text-white font-bold">{{ $member->department->name ?? 'Semua' }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Anggota Departemen --}}
                    @php
                        $soMembers = \App\Models\Member::where('department_id', $member->department_id)
                            ->where('status', 'Aktif')
                            ->with(['position', 'department'])
                            ->orderBy('position_id')
                            ->get();
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($soMembers as $so)
                        <div class="bg-white rounded-xl p-5 flex items-center gap-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#980D0D]/20 transition-all group">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 flex-shrink-0 flex items-center justify-center overflow-hidden border-2 {{ $so->id == $member->id ? 'border-[#980D0D] ring-2 ring-[#980D0D]/20' : 'border-gray-100' }}">
                                @if($so->photo)
                                    <img src="{{ Storage::url($so->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl font-black text-gray-400 group-hover:text-[#980D0D] transition-colors">{{ strtoupper(substr($so->full_name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-[#1E3A5F] text-sm truncate">{{ $so->full_name }}</p>
                                    @if($so->id == $member->id)
                                    <span class="px-1.5 py-0.5 bg-[#980D0D] text-white text-[8px] font-black rounded uppercase flex-shrink-0">Anda</span>
                                    @endif
                                </div>
                                <p class="text-xs text-[#980D0D] font-bold mt-0.5">{{ $so->position->name ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $so->university ?? '-' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-16 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-bold text-gray-300">Belum ada data anggota</p>
                            <p class="text-xs text-gray-300 mt-1">Departemen ini belum memiliki anggota aktif.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Link ke Halaman SO Lengkap --}}
                    <div class="text-center pt-2">
                        <a href="{{ route('member.organization') }}" class="inline-flex items-center gap-2 text-[#1E3A5F] hover:text-[#980D0D] font-bold text-sm transition-colors group">
                            Lihat Struktur Organisasi Lengkap
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
