@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-bold text-2xl text-navy leading-tight">
        {{ __('Portal Anggota') }}
    </h2>
    <span class="px-3 py-1 bg-blue-100 text-primary text-xs font-bold rounded-full border border-blue-200">
        {{ strtoupper($member->status ?? 'MEMBER') }}
    </span>
</div>
@endsection

@section('content')
<div class="py-12 bg-[#F4F6F9] min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div x-data="{ tab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- SIDEBAR: PROFILE CARD --}}
            <div class="space-y-6">
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
                    <p class="text-sm text-gray-400">{{ $member->member_code ?? 'ID: ISBA-NEW' }}</p>
                    
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">Angkatan {{ $member->batch_year ?? '-' }}</span>
                        <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-semibold rounded-lg">{{ $member->department->name ?? 'Umum' }}</span>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50 space-y-2">
                        <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-navy text-white' : 'text-gray-500 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Detail Profil
                        </button>
                        <button @click="tab = 'edit'" :class="tab === 'edit' ? 'bg-navy text-white' : 'text-gray-500 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profil
                        </button>
                        <button @click="tab = 'structure'" :class="tab === 'structure' ? 'bg-navy text-white' : 'text-gray-500 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Struktur Organisasi
                        </button>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT AREA --}}
            <div class="lg:col-span-2">
                
                {{-- TAB: DETAIL PROFILE --}}
                <div x-show="tab === 'profile'" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8 animate-fadeIn">
                    <div class="border-b border-gray-50 pb-4">
                        <h4 class="text-lg font-bold text-navy">Informasi Pribadi</h4>
                        <p class="text-xs text-gray-400">Data lengkap keanggotaan Anda di sistem.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Nama Lengkap</p>
                            <p class="text-gray-800 font-medium">{{ $member->full_name ?? Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">NIM / Identitas Kampus</p>
                            <p class="text-gray-800 font-medium font-mono">{{ $member->nim ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Email</p>
                            <p class="text-gray-800 font-medium">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Nomor WhatsApp</p>
                            <p class="text-gray-800 font-medium">{{ $member->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Departemen</p>
                            <p class="text-gray-800 font-medium">{{ $member->department->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Jabatan</p>
                            <p class="text-gray-800 font-medium">{{ $member->position->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50">
                         <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Alamat Tinggal</p>
                         <p class="text-gray-700 leading-relaxed">{{ $member->address ?? 'Belum melengkapi data alamat.' }}</p>
                    </div>
                </div>

                {{-- TAB: EDIT PROFILE --}}
                <div x-show="tab === 'edit'" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 animate-fadeIn">
                    <div class="border-b border-gray-50 pb-4 mb-8">
                        <h4 class="text-lg font-bold text-navy">Perbarui Profil</h4>
                        <p class="text-xs text-gray-400">Update data diri Anda agar tetap valid.</p>
                    </div>

                    <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ $member->phone }}" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Universitas</label>
                                <input type="text" name="university" value="{{ $member->university }}" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 transition-all">{{ $member->address }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto Profil (JPG/PNG, Max 2MB)</label>
                            <input type="file" name="photo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100 transition-all">
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/20 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB: STRUCTURE (SO) --}}
                <div x-show="tab === 'structure'" x-cloak class="space-y-6 animate-fadeIn">
                    <div class="bg-navy rounded-2xl p-8 text-white">
                        <h4 class="text-xl font-bold">Struktur Organisasi</h4>
                        <p class="text-blue-200 text-sm mt-1">Daftar pimpinan Departemen Anda: <span class="text-white font-bold">{{ $member->department->name ?? 'Semua' }}</span></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $soMembers = \App\Models\Member::where('department_id', $member->department_id)
                                ->with('position')
                                ->get();
                        @endphp
                        
                        @forelse($soMembers as $so)
                        <div class="bg-white rounded-xl p-4 flex items-center gap-4 border border-gray-100 shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center text-navy font-bold overflow-hidden">
                                @if($so->photo)
                                    <img src="{{ Storage::url($so->photo) }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($so->full_name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-navy text-sm">{{ $so->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ $so->position->name ?? '-' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-12 text-gray-400 italic">
                             Belum ada data pimpinan di departemen ini.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
