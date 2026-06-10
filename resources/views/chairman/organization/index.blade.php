@extends('layouts.admin')
@section('title', 'Struktur Organisasi')
@section('page-title', 'Struktur Organisasi ISBA JAYA')

@section('content')
<div class="py-6 space-y-8">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-navy/10 rounded-xl flex items-center justify-center text-navy">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-navy">Kepengurusan Organisasi</h2>
                <p class="text-gray-500 text-sm">Pratinjau struktur kepengurusan aktif ISBA JAYA.</p>
            </div>
        </div>
    </div>

    {{-- ORG TREE --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
        @include('components.org-tree')
    </div>

</div>
@endsection
