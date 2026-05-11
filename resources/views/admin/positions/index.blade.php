@extends('layouts.admin')
@section('title', 'Manajemen Jabatan')
@section('page-title', 'Kelola Semua Jabatan')

@section('content')
<div class="py-4 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-navy">Struktur Jabatan</h2>
            <p class="text-gray-500 text-sm mt-0.5">Kelola tingkatan dan peran dalam organisasi</p>
        </div>
        <button onclick="openPositionModal()" class="btn-primary text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jabatan
        </button>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="table-wrapper rounded-xl">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Nama Jabatan</th>
                        <th>Departemen</th>
                        <th class="text-center">Level</th>
                        <th class="text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positions as $pos)
                    <tr>
                        <td class="text-center text-gray-400">{{ $loop->iteration }}</td>
                        <td>
                            <span class="font-bold text-navy">{{ $pos->name }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $pos->department->color ?? '#ddd' }}"></div>
                                <span class="text-gray-600 text-sm">{{ $pos->department->name ?? 'Umum' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded uppercase">
                                Level {{ $pos->level ?? '1' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openPositionModal({{ $pos }})" class="p-1.5 text-primary hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.positions.destroy', $pos) }}" method="POST" onsubmit="return confirm('Hapus jabatan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-danger hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL: Position Form --}}
<div id="modal-position" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modal-title" class="font-semibold text-navy text-lg">Tambah Jabatan</h3>
            <button onclick="closeModal('modal-position')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form id="position-form" method="POST">
            @csrf
            <div id="method-container"></div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Jabatan</label>
                    <input type="text" name="name" id="pos-name" class="form-input" placeholder="Contoh: Kepala Departemen" required>
                </div>
                <div>
                    <label class="form-label">Departemen</label>
                    <select name="department_id" id="pos-dept" class="form-select" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Level Jabatan (Urutan Hirarki)</label>
                    <input type="number" name="level" id="pos-level" class="form-input" placeholder="Contoh: 1 (Tertinggi)" value="1">
                    <p class="text-[10px] text-gray-400 mt-1 italic">*Level 1 untuk Ketua/Kadept, level lebih tinggi untuk staf.</p>
                </div>
            </div>
            <div class="p-6 pt-0 flex gap-3">
                <button type="button" onclick="closeModal('modal-position')" class="btn-ghost border border-gray-300 flex-1">Batal</button>
                <button type="submit" class="btn-primary flex-1">Simpan Jabatan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openPositionModal(pos = null) {
        const modal = document.getElementById('modal-position');
        const form = document.getElementById('position-form');
        const title = document.getElementById('modal-title');
        const methodContainer = document.getElementById('method-container');
        
        if (pos) {
            title.textContent = 'Edit Jabatan';
            form.action = `/admin/positions/${pos.id}`;
            methodContainer.innerHTML = '@method("PUT")';
            document.getElementById('pos-name').value = pos.name;
            document.getElementById('pos-dept').value = pos.department_id;
            document.getElementById('pos-level').value = pos.level || 1;
        } else {
            title.textContent = 'Tambah Jabatan';
            form.action = "{{ route('admin.positions.store') }}";
            methodContainer.innerHTML = '';
            form.reset();
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush
