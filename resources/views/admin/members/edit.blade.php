@extends('layouts.admin')
@section('title', 'Edit Anggota')
@section('page-title', 'Edit Anggota')

@section('content')
<div class="py-4 max-w-3xl">

  {{-- Breadcrumb --}}
  <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('admin.members.index') }}" class="hover:text-primary">Data Anggota</a>
    <span>›</span>
    <a href="{{ route('admin.members.show', $member) }}" class="hover:text-primary">{{ $member->full_name }}</a>
    <span>›</span>
    <span class="text-navy font-medium">Edit Data</span>
  </nav>

  <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data"
    class="space-y-5">
    @csrf
    @method('PUT')

    {{-- SECTION 1: Informasi Pribadi --}}
    <div class="card">
      <div class="card-header">
        <div class="flex items-center gap-3">
          <div class="w-1 h-6 bg-primary rounded-full"></div>
          <h3 class="font-semibold text-navy">Informasi Pribadi</h3>
        </div>
      </div>
      <div class="card-body space-y-4">

        {{-- Foto Upload --}}
        <div class="flex flex-col items-center gap-3 pb-4 border-b border-gray-100">
          <div class="relative">
            <div id="photo-preview"
              class="w-24 h-24 rounded-full bg-gray-100 border-2 border-gray-300
              flex items-center justify-center overflow-hidden cursor-pointer
              hover:border-primary transition-colors"
              onclick="document.getElementById('photo-input').click()">
              @if($member->photo)
              <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover" alt="Foto">
              @else
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              @endif
            </div>
          </div>
          <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden"
            onchange="previewPhoto(this)">
          <div class="text-center">
            <button type="button" onclick="document.getElementById('photo-input').click()"
              class="text-sm text-primary hover:underline font-medium">Ubah Foto</button>
            <p class="text-xs text-gray-400 mt-0.5">JPG, PNG atau GIF — Maks. 2MB</p>
          </div>
          @error('photo')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Nama Lengkap --}}
        <div>
          <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}"
            class="form-input @error('full_name') border-red-400 @enderror"
            placeholder="Masukkan nama sesuai KTP">
          @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- NIM + Gender --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">NIM <span class="text-red-500">*</span></label>
            <input type="text" name="nim" value="{{ old('nim', $member->nim) }}"
              class="form-input @error('nim') border-red-400 @enderror"
              placeholder="Nomor Induk Mahasiswa">
            @error('nim')<p class="form-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" class="form-select @error('gender') border-red-400 @enderror">
              <option value="">Pilih</option>
              <option value="Laki-laki" {{ old('gender', $member->gender)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="Perempuan" {{ old('gender', $member->gender)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<p class="form-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Tempat + Tanggal Lahir --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="birth_place" value="{{ old('birth_place', $member->birth_place) }}"
              class="form-input" placeholder="Kota">
          </div>
          <div>
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}"
              class="form-input">
          </div>
        </div>

        {{-- No HP + Email --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
              class="form-input" placeholder="+62 812 3456 7890">
          </div>
          <div>
            <label class="form-label">Email Pribadi</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}"
              class="form-input" placeholder="nama@email.com">
          </div>
        </div>

        {{-- Alamat --}}
        <div>
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="address" rows="3" class="form-input resize-none"
            placeholder="Jl. Contoh No. 1, Kecamatan, Kota">{{ old('address', $member->address) }}</textarea>
        </div>
      </div>
    </div>

    {{-- SECTION 2: Informasi Keanggotaan --}}
    <div class="card">
      <div class="card-header">
        <div class="flex items-center gap-3">
          <div class="w-1 h-6 bg-amber rounded-full"></div>
          <h3 class="font-semibold text-navy">Informasi Keanggotaan</h3>
        </div>
      </div>
      <div class="card-body space-y-4">

        {{-- Departemen + Jabatan --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Departemen</label>
            <select name="department_id" id="department-select"
              class="form-select" onchange="loadPositions(this.value)">
              <option value="">Pilih Departemen</option>
              @foreach($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id', $member->department_id)==$dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="form-label">Jabatan</label>
            <select name="position_id" id="position-select" class="form-select">
              <option value="">Pilih Jabatan</option>
              @foreach($positions as $pos)
              <option value="{{ $pos->id }}" {{ old('position_id', $member->position_id)==$pos->id ? 'selected' : '' }}>
                {{ $pos->name }} — {{ $pos->level }}
              </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Tanggal Bergabung + Status --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Tanggal Bergabung</label>
            <input type="date" name="joined_at" value="{{ old('joined_at', $member->joined_at ? $member->joined_at->format('Y-m-d') : '') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">Status Keanggotaan <span class="text-red-500">*</span></label>
            <div class="flex gap-4 mt-2">
              @foreach(['Aktif','Tidak Aktif','Alumni','Pending'] as $s)
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="status" value="{{ $s }}"
                  {{ old('status', $member->status) == $s ? 'checked' : '' }}
                  class="text-primary">
                <span class="text-sm text-gray-700">{{ $s }}</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Angkatan + Universitas --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Angkatan</label>
            <input type="text" name="batch_year" value="{{ old('batch_year', $member->batch_year) }}"
              class="form-input" placeholder="2023" maxlength="4">
          </div>
          <div>
            <label class="form-label">Universitas / Instansi</label>
            <input type="text" name="university" value="{{ old('university', $member->university) }}"
              class="form-input" placeholder="Nama Kampus">
          </div>
        </div>
      </div>
    </div>

    {{-- SECTION 3: Akun Sistem --}}
    <div class="card">
      <div class="card-header">
        <div class="flex items-center gap-3">
          <div class="w-1 h-6 bg-success rounded-full"></div>
          <h3 class="font-semibold text-navy">Akun Sistem</h3>
        </div>
      </div>
      <div class="card-body">
        @if($member->user)
        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
          <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-navy">Akun Terhubung</p>
            <p class="text-xs text-gray-500">{{ $member->user->email }} ({{ $member->user->getRoleNames()->first() }})</p>
          </div>
          <a href="{{ route('admin.settings.index') }}" class="ml-auto text-xs text-primary hover:underline">Kelola Akun</a>
        </div>
        @else
        <p class="text-sm text-gray-500 italic">Anggota ini belum memiliki akun sistem.</p>
        @endif
      </div>
    </div>

    {{-- FORM FOOTER --}}
    <div class="flex items-center justify-end gap-3 pb-6">
      <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost border border-gray-300">
        Batal
      </a>
      <button type="submit" class="btn-primary px-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const preview = document.getElementById('photo-preview');
      preview.innerHTML = `<img src="${e.target.result}"
        class="w-full h-full object-cover" alt="Preview">`;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function loadPositions(deptId) {
  const select = document.getElementById('position-select');
  select.innerHTML = '<option value="">Memuat...</option>';
  if (!deptId) {
    select.innerHTML = '<option value="">Pilih Jabatan</option>';
    return;
  }
  fetch(`/admin/positions/by-department/${deptId}`)
    .then(r => r.json())
    .then(data => {
      select.innerHTML = '<option value="">Pilih Jabatan</option>';
      data.forEach(p => {
        select.innerHTML += `<option value="${p.id}">${p.name} — ${p.level}</option>`;
      });
    });
}
</script>
@endpush
