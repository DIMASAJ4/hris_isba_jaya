@extends('layouts.admin')
@section('title', 'Departemen')
@section('page-title', 'Departemen & Struktur')

@section('content')
<div class="py-4 space-y-6">

  {{-- HEADER --}}
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-navy">Manajemen Departemen</h2>
      <p class="text-gray-500 text-sm mt-0.5">Kelola divisi dan struktur organisasi ISBA JAYA</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.positions.index') }}" class="btn-ghost border border-gray-300 text-sm">
        Kelola Semua Jabatan
      </a>
      <button onclick="openDeptModal()" class="btn-primary text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Departemen
      </button>
    </div>
  </div>

  {{-- DEPARTMENT GRID --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($departments as $dept)
    <div class="card overflow-hidden group hover:shadow-md transition-shadow">
      {{-- Color Strip --}}
      <div class="h-2" style="background-color: {{ $dept->color }}"></div>
      
      <div class="p-5">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm"
            style="background-color: {{ $dept->color }}">
            {{-- Icon placeholder (Lucide/Heroicons logic) --}}
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="openDeptModal({{ $dept }})" class="p-1.5 text-gray-400 hover:text-primary rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Hapus departemen ini?')">
              @csrf @method('DELETE')
              <button class="p-1.5 text-gray-400 hover:text-danger rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </form>
          </div>
        </div>

        <h3 class="font-bold text-navy text-lg">{{ $dept->name }}</h3>
        <p class="text-gray-500 text-xs mt-1 line-clamp-2 h-8">{{ $dept->description ?? 'Tidak ada deskripsi.' }}</p>
        
        <div class="mt-6 flex items-center justify-between border-t border-gray-50 pt-4">
          <div class="text-center">
            <p class="text-xs text-gray-400 uppercase font-semibold">Anggota</p>
            <p class="font-bold text-navy">{{ $dept->members_count }}</p>
          </div>
          <div class="text-center">
            <p class="text-xs text-gray-400 uppercase font-semibold">Jabatan</p>
            <p class="font-bold text-navy">{{ $dept->positions_count }}</p>
          </div>
          <div class="text-center">
             <a href="{{ route('admin.members.index', ['department_id' => $dept->id]) }}" 
                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
             </a>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    {{-- Add New Card --}}
    <button onclick="openDeptModal()" 
      class="card border-2 border-dashed border-gray-200 bg-gray-50/50 flex flex-col items-center justify-center p-6 hover:border-primary hover:bg-blue-50 transition-all group">
      <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 group-hover:bg-primary group-hover:text-white mb-3 transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
      </div>
      <p class="text-sm font-semibold text-gray-500 group-hover:text-primary">Tambah Departemen</p>
    </button>
  </div>
</div>

{{-- MODAL: Department --}}
<div id="modal-dept" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
      <h3 id="modal-title" class="font-semibold text-navy text-lg">Tambah Departemen</h3>
      <button onclick="closeModal('modal-dept')" class="text-gray-400 hover:text-gray-600">✕</button>
    </div>
    <form id="dept-form" method="POST">
      @csrf
      <div id="method-container"></div>
      <div class="p-6 space-y-4">
        <div>
          <label class="form-label">Nama Departemen</label>
          <input type="text" name="name" id="dept-name" class="form-input" placeholder="Contoh: Sumber Daya Mahasiswa" required>
        </div>
        <div>
          <label class="form-label">Deskripsi Singkat</label>
          <textarea name="description" id="dept-desc" class="form-input resize-none" rows="2" placeholder="Tujuan divisi..."></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Warna Branding</label>
            <div class="flex gap-2">
               <input type="color" name="color" id="dept-color" class="h-10 w-12 rounded-lg border-gray-300 cursor-pointer" value="#2E86C1">
               <input type="text" id="color-text" class="form-input font-mono text-xs" readonly value="#2E86C1">
            </div>
          </div>
          <div>
            <label class="form-label">Status</label>
            <select name="is_active" id="dept-status" class="form-select">
              <option value="1">Aktif</option>
              <option value="0">Non-Aktif</option>
            </select>
          </div>
        </div>
      </div>
      <div class="p-6 pt-0 flex gap-3">
        <button type="button" onclick="closeModal('modal-dept')" class="btn-ghost border border-gray-300 flex-1">Batal</button>
        <button type="submit" class="btn-primary flex-1">Simpan Departemen</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function openDeptModal(dept = null) {
    const modal = document.getElementById('modal-dept');
    const form = document.getElementById('dept-form');
    const title = document.getElementById('modal-title');
    const methodContainer = document.getElementById('method-container');
    
    if (dept) {
      title.textContent = 'Edit Departemen';
      form.action = `/admin/departments/${dept.id}`;
      methodContainer.innerHTML = '@method("PUT")';
      document.getElementById('dept-name').value = dept.name;
      document.getElementById('dept-desc').value = dept.description || '';
      document.getElementById('dept-color').value = dept.color || '#2E86C1';
      document.getElementById('color-text').value = dept.color || '#2E86C1';
      document.getElementById('dept-status').value = dept.is_active;
    } else {
      title.textContent = 'Tambah Departemen';
      form.action = "{{ route('admin.departments.store') }}";
      methodContainer.innerHTML = '';
      form.reset();
      document.getElementById('dept-color').value = '#2E86C1';
      document.getElementById('color-text').value = '#2E86C1';
    }
    
    modal.classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }

  document.getElementById('dept-color').addEventListener('input', function() {
    document.getElementById('color-text').value = this.value.toUpperCase();
  });
</script>
@endpush
