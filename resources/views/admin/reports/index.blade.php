@extends('layouts.admin')
@section('title', 'Pusat Laporan')
@section('page-title', 'Laporan Keanggotaan')

@section('content')
<div class="py-4 space-y-6">

  {{-- FILTER BOX --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
      </div>
      <div>
        <h3 class="font-bold text-navy">Filter Laporan</h3>
        <p class="text-xs text-gray-500">Sesuaikan data yang ingin Anda tampilkan dalam laporan.</p>
      </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="form-label">Departemen</label>
        <select name="department_id" class="form-select">
          <option value="">Semua Departemen</option>
          @foreach($departments as $dept)
          <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
            {{ $dept->name }}
          </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="form-label">Status Anggota</label>
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          @foreach(['Aktif','Tidak Aktif','Alumni','Pending'] as $s)
          <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="form-label">Tahun Angkatan</label>
        <input type="text" name="batch_year" value="{{ request('batch_year') }}" 
          class="form-input" placeholder="Contoh: 2023">
      </div>

      <div class="flex items-end gap-2">
        <button type="submit" class="btn-primary flex-1">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Tampilkan
        </button>
        <a href="{{ route('admin.reports.index') }}" class="btn-ghost border border-gray-300 p-2.5 rounded-xl">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </a>
      </div>
    </form>
  </div>

  {{-- RESULTS TABLE --}}
  <div class="card">
    <div class="card-header justify-between">
      <h3 class="font-semibold text-navy">Pratinjau Data ({{ $members->count() }} Anggota)</h3>
      
      @if($members->count() > 0)
      <div class="flex gap-2">
        <a href="{{ route('admin.reports.export.excel', request()->all()) }}" class="btn-success text-xs py-1.5">
          Download Excel
        </a>
        <a href="{{ route('admin.reports.export.pdf', request()->all()) }}" class="btn-danger text-xs py-1.5">
          Download PDF
        </a>
      </div>
      @endif
    </div>

    <div class="table-wrapper rounded-none border-0">
      <table class="table">
        <thead>
          <tr>
            <th class="w-12">No</th>
            <th>Nama Lengkap</th>
            <th>NIM</th>
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($members as $member)
          <tr>
            <td class="text-center text-gray-400 text-xs">{{ $loop->iteration }}</td>
            <td class="font-medium text-navy text-sm">{{ $member->full_name }}</td>
            <td class="font-mono text-xs text-gray-500">{{ $member->nim }}</td>
            <td class="text-sm text-gray-600">{{ $member->department->name ?? '-' }}</td>
            <td class="text-sm text-gray-600">{{ $member->position->name ?? '-' }}</td>
            <td>
              <span class="badge-{{ strtolower(str_replace(' ','-',$member->status)) }} text-[10px]">
                {{ $member->status }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-16 text-gray-400">
              Silakan sesuaikan filter dan klik "Tampilkan" untuk melihat data.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
