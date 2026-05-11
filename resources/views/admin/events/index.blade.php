@extends('layouts.admin')
@section('title', 'Berita Acara')
@section('page-title', 'Daftar Berita Acara')

@section('content')
<div class="py-6 space-y-6">

    {{-- STATS & SEARCH --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Search & Filter Card --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Cari Acara</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul atau Kode Berita Acara..." class="form-input pl-10">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full justify-center">Filter</button>
                </div>
            </form>
        </div>

        {{-- Add Button Card --}}
        <div class="bg-navy p-6 rounded-2xl shadow-xl shadow-navy/20 flex flex-col justify-center items-center text-center space-y-3">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <p class="text-xs text-blue-200 font-medium">Buat Dokumen Baru</p>
            <a href="{{ route('admin.events.create') }}" class="w-full py-2 bg-white text-navy rounded-xl text-xs font-black hover:bg-gray-100 transition-all uppercase tracking-tighter">Tambah Berita Acara</a>
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
                        <th>Lokasi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td class="text-center text-gray-400">{{ ($events->currentPage()-1) * $events->perPage() + $loop->iteration }}</td>
                        <td><span class="font-mono text-xs font-bold text-primary bg-blue-50 px-2 py-1 rounded">{{ $event->event_code }}</span></td>
                        <td><p class="font-bold text-navy truncate max-w-[200px]">{{ $event->title }}</p></td>
                        <td><span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</span></td>
                        <td><span class="text-sm text-gray-500">{{ $event->location }}</span></td>
                        <td class="text-center">
                            @if($event->status == 'Selesai')
                                <span class="badge-success">Selesai</span>
                            @elseif($event->status == 'Draft')
                                <span class="badge-warning">Draft</span>
                            @else
                                <span class="badge-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.events.show', $event) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus Berita Acara ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-20 text-gray-400 italic">Belum ada data berita acara.</td></tr>
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
