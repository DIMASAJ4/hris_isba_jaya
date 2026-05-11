@extends('layouts.admin')
@section('title', 'Detail Berita Acara')
@section('page-title', 'Detail Berita Acara: ' . $event->event_code)

@section('content')
<div class="py-6 space-y-6">

    {{-- ACTIONS BAR --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-navy font-bold transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.edit', $event) }}" class="btn-ghost text-xs border border-gray-200">Edit Dokumen</a>
            <a href="{{ route('admin.events.pdf', $event) }}" class="btn-primary text-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- MAIN INFO --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-8 space-y-8">
                <div>
                    <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Agenda & Deskripsi</h4>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        {{ $event->description }}
                    </div>
                </div>

                @if($event->notes)
                <div>
                    <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Catatan Tambahan</h4>
                    <div class="text-gray-600 text-sm italic bg-amber-50/30 p-6 rounded-2xl border border-amber-100">
                        {{ $event->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- SIDE INFO --}}
        <div class="space-y-6">
            <div class="card p-6 space-y-6">
                <h4 class="text-sm font-black text-navy uppercase tracking-widest border-b border-gray-50 pb-4">Metadata Dokumen</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase">Judul Acara</p>
                        <p class="text-navy font-bold">{{ $event->title }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase">Tanggal</p>
                        <p class="text-navy font-bold">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase">Lokasi</p>
                        <p class="text-navy font-bold">{{ $event->location }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase">Status</p>
                        @if($event->status == 'Selesai')
                            <span class="badge-success inline-block mt-1">Selesai</span>
                        @elseif($event->status == 'Draft')
                            <span class="badge-warning inline-block mt-1">Draft</span>
                        @else
                            <span class="badge-danger inline-block mt-1">Dibatalkan</span>
                        @endif
                    </div>
                    <div class="pt-4 border-t border-gray-50">
                        <p class="text-[10px] text-gray-400 font-black uppercase">Dibuat Oleh</p>
                        <p class="text-navy font-medium text-sm">{{ $event->creator->name }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">{{ $event->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- PDF PREVIEW CARD --}}
            <div class="bg-gray-100 p-6 rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-center space-y-3 opacity-60">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest leading-none">Siap Cetak</p>
                <p class="text-[10px] text-gray-400">Gunakan tombol download untuk mengunduh versi PDF resmi.</p>
            </div>
        </div>
    </div>

</div>
@endsection
