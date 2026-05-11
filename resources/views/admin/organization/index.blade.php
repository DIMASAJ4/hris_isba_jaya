@extends('layouts.admin')
@section('title', 'Struktur Organisasi')
@section('page-title', 'Struktur Organisasi ISBA JAYA')

@section('content')
<div class="py-6 space-y-8">

    {{-- HEADER --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-navy/10 rounded-xl flex items-center justify-center text-navy">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-navy">Data Kepengurusan</h2>
                <p class="text-gray-500 text-sm">Daftar pemegang jabatan per departemen periode {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    @foreach($departments as $dept)
    <div class="space-y-4">
        {{-- DEPT HEADER --}}
        <div class="flex items-center gap-3 px-2">
            <div class="w-3 h-8 rounded-full" style="background-color: {{ $dept->color ?? '#1E3A5F' }}"></div>
            <h3 class="text-lg font-black text-navy uppercase tracking-wider">{{ $dept->name }}</h3>
            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full uppercase">
                {{ $dept->positions->count() }} Jabatan
            </span>
        </div>

        {{-- TABLE CARD --}}
        <div class="card overflow-hidden">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th class="w-1/4">Jabatan</th>
                            <th class="text-center w-24">Level</th>
                            <th>Pemegang Jabatan</th>
                            <th>NIM</th>
                            <th class="text-center w-32">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dept->positions as $pos)
                        <tr>
                            <td class="text-center text-gray-400">{{ $loop->iteration }}</td>
                            <td>
                                <span class="font-bold text-navy">{{ $pos->name }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-xs font-medium text-gray-500">Lvl {{ $pos->level }}</span>
                            </td>
                            <td>
                                @php $activeMember = $pos->members->where('status', 'Aktif')->first(); @endphp
                                @if($activeMember)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                        @if($activeMember->photo)
                                            <img src="{{ asset('storage/' . $activeMember->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-navy font-bold text-xs uppercase">
                                                {{ substr($activeMember->full_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-navy text-sm">{{ $activeMember->full_name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $activeMember->university ?? 'ISBA JAYA' }}</p>
                                    </div>
                                </div>
                                @else
                                <span class="text-amber-600 font-bold text-sm italic">Belum ada penjabat</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-gray-500">
                                {{ $activeMember ? $activeMember->nim : '-' }}
                            </td>
                            <td class="text-center">
                                @if($activeMember)
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase border border-green-100">
                                    {{ $activeMember->status }}
                                </span>
                                @else
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-black rounded-full uppercase border border-gray-100">
                                    Kosong
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-400 italic">Belum ada data jabatan untuk departemen ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection
