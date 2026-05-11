@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-bold text-2xl text-navy leading-tight">
        {{ __('Struktur Organisasi') }}
    </h2>
    <span class="px-3 py-1 bg-navy text-white text-[10px] font-black rounded-full border border-navy/20 uppercase">
        Periode {{ date('Y') }}
    </span>
</div>
@endsection

@section('content')
<div class="py-12 bg-[#F4F6F9] min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-navy mb-2">Struktur Kepengurusan ISBA JAYA</h3>
            <p class="text-gray-500 text-sm">Berikut adalah daftar pemegang jabatan aktif di seluruh departemen.</p>
        </div>

        @foreach($departments as $dept)
        <div class="space-y-4">
            {{-- DEPT HEADER --}}
            <div class="flex items-center gap-3 px-2">
                <div class="w-3 h-8 rounded-full" style="background-color: {{ $dept->color ?? '#1E3A5F' }}"></div>
                <h3 class="text-lg font-black text-navy uppercase tracking-wider">{{ $dept->name }}</h3>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-16">No</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jabatan</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($dept->positions as $pos)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-gray-400 text-sm">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-navy text-sm">{{ $pos->name }}</span>
                                    <p class="text-[10px] text-gray-400 font-medium">Level {{ $pos->level }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php $activeMember = $pos->members->where('status', 'Aktif')->first(); @endphp
                                    @if($activeMember)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                            @if($activeMember->photo)
                                                <img src="{{ asset('storage/' . $activeMember->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-navy font-bold text-xs">
                                                    {{ substr($activeMember->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="font-bold text-gray-700 text-sm">{{ $activeMember->full_name }}</span>
                                    </div>
                                    @else
                                    <span class="text-amber-600 font-bold text-xs italic">Belum ada penjabat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                    {{ $activeMember ? $activeMember->nim : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($activeMember)
                                    <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase border border-green-100">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-gray-50 text-gray-300 text-[10px] font-black rounded-full uppercase border border-gray-100 text-center">
                                        -
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic text-sm">Belum ada data jabatan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
