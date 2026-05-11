@extends('layouts.admin')
@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

@section('content')
<div class="py-4 space-y-5">

  {{-- PAGE HEADER --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-navy">Data Anggota</h2>
      <p class="text-gray-500 text-sm mt-0.5">
        Kelola seluruh data keanggotaan ISBA JAYA
      </p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
      {{-- Import Excel --}}
      <button onclick="document.getElementById('modal-import').classList.remove('hidden')"
        class="btn-success text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
        </svg>
        Import Excel
      </button>

      {{-- Export Dropdown --}}
      <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="btn-ghost border border-gray-300 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
          </svg>
          Export
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div x-show="open" @click.away="open = false"
          class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-20">
          <a href="{{ route('admin.members.export.excel') }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <span class="w-5 h-5 bg-green-100 rounded text-green-700 text-xs flex items-center justify-center font-bold">XL</span>
            Export Excel
          </a>
          <a href="{{ route('admin.members.export.pdf') }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <span class="w-5 h-5 bg-red-100 rounded text-red-700 text-xs flex items-center justify-center font-bold">PDF</span>
            Export PDF
          </a>
        </div>
      </div>

      {{-- Tambah Anggota --}}
      <a href="{{ route('admin.members.create') }}" class="btn-primary text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Anggota
      </a>
    </div>
  </div>

  {{-- SEARCH & FILTER --}}
  <div class="card p-4">
    <form method="GET" action="{{ route('admin.members.index') }}"
      class="flex flex-col sm:flex-row gap-3 items-end">

      {{-- Search --}}
      <div class="flex-1">
        <label class="form-label">Cari Anggota</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" name="search" value="{{ request('search') }}"
            class="form-input pl-9" placeholder="Cari nama, NIM, atau email...">
        </div>
      </div>

      {{-- Filter Departemen --}}
      <div class="w-full sm:w-48">
        <label class="form-label">Departemen</label>
        <select name="department_id" class="form-select">
          <option value="">Semua Departemen</option>
          @foreach($departments as $dept)
          <option value="{{ $dept->id }}"
            {{ request('department_id') == $dept->id ? 'selected' : '' }}>
            {{ $dept->name }}
          </option>
          @endforeach
        </select>
      </div>

      {{-- Filter Status --}}
      <div class="w-full sm:w-40">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          @foreach(['Aktif','Tidak Aktif','Alumni','Pending'] as $s)
          <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
            {{ $s }}
          </option>
          @endforeach
        </select>
      </div>

      {{-- Buttons --}}
      <div class="flex gap-2">
        <button type="submit" class="btn-primary text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Cari
        </button>
        @if(request()->hasAny(['search','department_id','status']))
        <a href="{{ route('admin.members.index') }}"
          class="btn-ghost border border-gray-300 text-sm text-red-500 hover:text-red-600">
          Reset
        </a>
        @endif
      </div>
    </form>
  </div>

  {{-- DATA TABLE --}}
  <div class="card">
    <div class="table-wrapper rounded-xl">
      <table class="table">
        <thead>
          <tr>
            <th class="w-12">No</th>
            <th class="w-12">Foto</th>
            <th>Nama Lengkap</th>
            <th>NIM</th>
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th class="text-center w-28">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($members as $member)
          <tr>
            {{-- No --}}
            <td class="text-gray-400 text-center">
              {{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}
            </td>

            {{-- Foto --}}
            <td>
              @if($member->photo)
              <img src="{{ Storage::url($member->photo) }}"
                class="w-9 h-9 rounded-full object-cover border-2 border-gray-100"
                alt="{{ $member->full_name }}">
              @else
              <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center
                justify-center text-primary text-sm font-bold border-2 border-gray-100">
                {{ strtoupper(substr($member->full_name, 0, 1)) }}
              </div>
              @endif
            </td>

            {{-- Nama --}}
            <td>
              <a href="{{ route('admin.members.show', $member) }}"
                class="font-semibold text-navy hover:text-primary transition-colors">
                {{ $member->full_name }}
              </a>
              <p class="text-xs text-gray-400">{{ $member->member_code }}</p>
            </td>

            {{-- NIM --}}
            <td class="font-mono text-gray-500 text-xs">{{ $member->nim }}</td>

            {{-- Departemen --}}
            <td>{{ $member->department->name ?? '-' }}</td>

            {{-- Jabatan --}}
            <td class="text-gray-600">{{ $member->position->name ?? '-' }}</td>

            {{-- Status --}}
            <td>
              @php
              $statusClass = match($member->status) {
                'Aktif'      => 'badge-aktif',
                'Tidak Aktif'=> 'badge-tidak-aktif',
                'Alumni'     => 'badge-alumni',
                'Pending'    => 'badge-pending',
                default      => 'badge-pending'
              };
              @endphp
              <span class="{{ $statusClass }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                {{ $member->status }}
              </span>
            </td>

            {{-- Aksi --}}
            <td>
              <div class="flex items-center justify-center gap-1">
                {{-- Detail --}}
                <a href="{{ route('admin.members.show', $member) }}"
                  class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                  title="Detail">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </a>

                {{-- Edit --}}
                <a href="{{ route('admin.members.edit', $member) }}"
                  class="p-1.5 text-primary hover:text-primary-dark hover:bg-blue-50 rounded-lg transition-colors"
                  title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>

                {{-- Hapus --}}
                <button onclick="confirmDelete('{{ $member->id }}', '{{ $member->full_name }}')"
                  class="p-1.5 text-danger hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  title="Hapus">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>

                {{-- Hidden delete form --}}
                <form id="delete-form-{{ $member->id }}"
                  action="{{ route('admin.members.destroy', $member) }}"
                  method="POST" class="hidden">
                  @csrf @method('DELETE')
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center
                  justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada data anggota</p>
                <p class="text-gray-400 text-sm mt-1">Tambahkan anggota pertama Anda</p>
                <a href="{{ route('admin.members.create') }}"
                  class="btn-primary text-sm mt-4 inline-flex">
                  + Tambah Anggota
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- PAGINATION --}}
    @if($members->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
      <p class="text-sm text-gray-500">
        Menampilkan {{ $members->firstItem() }}–{{ $members->lastItem() }}
        dari <span class="font-semibold text-navy">{{ $members->total() }}</span> anggota
      </p>
      <div class="flex items-center gap-1">
        {{-- Prev --}}
        @if($members->onFirstPage())
        <span class="px-2.5 py-1.5 text-gray-300 border border-gray-200 rounded-lg text-sm cursor-not-allowed">←</span>
        @else
        <a href="{{ $members->previousPageUrl() }}"
          class="px-2.5 py-1.5 text-gray-600 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">←</a>
        @endif

        {{-- Pages --}}
        @foreach($members->getUrlRange(1, $members->lastPage()) as $page => $url)
        <a href="{{ $url }}"
          class="px-3 py-1.5 rounded-lg text-sm font-medium
          {{ $page == $members->currentPage()
            ? 'bg-primary text-white'
            : 'text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
          {{ $page }}
        </a>
        @endforeach

        {{-- Next --}}
        @if($members->hasMorePages())
        <a href="{{ $members->nextPageUrl() }}"
          class="px-2.5 py-1.5 text-gray-600 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">→</a>
        @else
        <span class="px-2.5 py-1.5 text-gray-300 border border-gray-200 rounded-lg text-sm cursor-not-allowed">→</span>
        @endif
      </div>
    </div>
    @endif
  </div>
</div>

{{-- MODAL: Import Excel --}}
<div id="modal-import" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
    <div class="p-6 border-b border-gray-100">
      <h3 class="font-semibold text-navy text-lg">Import Data Anggota</h3>
      <p class="text-sm text-gray-500 mt-1">Upload file Excel untuk import data anggota secara massal</p>
    </div>
    <form action="{{ route('admin.members.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="p-6 space-y-4">
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center
          hover:border-primary transition-colors cursor-pointer"
          onclick="document.getElementById('import-file').click()">
          <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
          </svg>
          <p class="text-sm text-gray-600 font-medium">Klik untuk upload file</p>
          <p class="text-xs text-gray-400 mt-1">Format: .xlsx, .xls, .csv (maks. 5MB)</p>
          <input id="import-file" type="file" name="file" accept=".xlsx,.xls,.csv" class="hidden"
            onchange="document.getElementById('file-name').textContent = this.files[0].name">
        </div>
        <p id="file-name" class="text-sm text-primary text-center"></p>
        <div class="bg-blue-50 rounded-lg p-3">
          <p class="text-xs text-blue-700 font-medium mb-1">Kolom yang diperlukan:</p>
          <p class="text-xs text-blue-600">
            nama_lengkap, nim, jenis_kelamin, departemen, jabatan, status, angkatan, universitas
          </p>
        </div>
      </div>
      <div class="p-6 pt-0 flex gap-3">
        <button type="button"
          onclick="document.getElementById('modal-import').classList.add('hidden')"
          class="btn-ghost border border-gray-300 flex-1">Batal</button>
        <button type="submit" class="btn-primary flex-1">
          Upload & Import
        </button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL: Konfirmasi Hapus --}}
<div id="modal-delete" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
    <div class="p-6 text-center">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-navy mb-2">Hapus Anggota?</h3>
      <p class="text-gray-500 text-sm">
        Anda akan menghapus data anggota
        <span id="delete-name" class="font-semibold text-navy"></span>.
        Tindakan ini tidak dapat dibatalkan.
      </p>
    </div>
    <div class="px-6 pb-6 flex gap-3">
      <button onclick="document.getElementById('modal-delete').classList.add('hidden')"
        class="btn-ghost border border-gray-300 flex-1">Batal</button>
      <button id="btn-confirm-delete" class="btn-danger flex-1">Ya, Hapus</button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, name) {
  document.getElementById('delete-name').textContent = name;
  document.getElementById('btn-confirm-delete').onclick = () => {
    document.getElementById('delete-form-' + id).submit();
  };
  document.getElementById('modal-delete').classList.remove('hidden');
}
</script>
@endpush
