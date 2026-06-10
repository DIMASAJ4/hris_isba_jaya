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

        {{-- ORG TREE --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
            @include('components.org-tree')
        </div>

    </div>
</div>
@endsection
