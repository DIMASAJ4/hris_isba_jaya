@extends('layouts.admin')
@section('title', 'Tambah Anggota')
@section('page-title', 'Tambah Anggota')

@section('content')
<div class="py-4 max-w-3xl">

  {{-- Breadcrumb --}}
  <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('admin.members.index') }}" class="hover:text-primary">Data Anggota</a>
    <span>›</span>
    <span class="text-navy font-medium">Tambah Anggota Baru</span>
  </nav>

  <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data"
    class="space-y-5">
    @csrf

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
              class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-gray-300
              flex items-center justify-center overflow-hidden cursor-pointer
              hover:border-primary transition-colors"
              onclick="document.getElementById('photo-input').click()">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
          </div>
          <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden"
            onchange="previewPhoto(this)">
          <div class="text-center">
            <button type="button" onclick="document.getElementById('photo-input').click()"
              class="text-sm text-primary hover:underline font-medium">Upload Foto</button>
            <p class="text-xs text-gray-400 mt-0.5">JPG, PNG atau GIF — Maks. 2MB</p>
          </div>
          @error('photo')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Nama Lengkap --}}
        <div>
          <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="full_name" value="{{ old('full_name') }}"
            class="form-input @error('full_name') border-red-400 @enderror"
            placeholder="Masukkan nama sesuai KTP">
          @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- NIM + Gender --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">NIM <span class="text-red-500">*</span></label>
            <input type="text" name="nim" value="{{ old('nim') }}"
              class="form-input @error('nim') border-red-400 @enderror"
              placeholder="Nomor Induk Mahasiswa">
            @error('nim')<p class="form-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" class="form-select @error('gender') border-red-400 @enderror">
              <option value="">Pilih</option>
              <option value="Laki-laki" {{ old('gender')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="Perempuan" {{ old('gender')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<p class="form-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Tempat + Tanggal Lahir --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="birth_place" value="{{ old('birth_place') }}"
              class="form-input" placeholder="Kota">
          </div>
          <div>
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
              class="form-input">
          </div>
        </div>

        {{-- No HP + Email --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
              class="form-input" placeholder="+62 812 3456 7890">
          </div>
          <div>
            <label class="form-label">Email Pribadi</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="form-input" placeholder="nama@email.com">
          </div>
        </div>

        {{-- Alamat --}}
        <div>
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="address" rows="3" class="form-input resize-none"
            placeholder="Jl. Contoh No. 1, Kecamatan, Kota">{{ old('address') }}</textarea>
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
              <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="form-label">Jabatan</label>
            <select name="position_id" id="position-select" class="form-select">
              <option value="">Pilih Jabatan</option>
            </select>
          </div>
        </div>

        {{-- Tanggal Bergabung + Status --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Tanggal Bergabung</label>
            <input type="date" name="joined_at" value="{{ old('joined_at') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">Status Keanggotaan <span class="text-red-500">*</span></label>
            <div class="flex gap-4 mt-2">
              @foreach(['Aktif','Tidak Aktif','Pending'] as $s)
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="status" value="{{ $s }}"
                  {{ old('status','Aktif') == $s ? 'checked' : '' }}
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
            <input type="text" name="batch_year" value="{{ old('batch_year') }}"
              class="form-input" placeholder="2023" maxlength="4">
          </div>
          <div>
            <label class="form-label">Universitas / Instansi</label>
            <input type="text" name="university" value="{{ old('university') }}"
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
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" id="create-account-toggle" name="create_account" value="1"
            {{ old('create_account') ? 'checked' : '' }}
            onchange="toggleAccountFields(this.checked)"
            class="rounded text-primary">
          <span class="text-sm text-gray-600">Buatkan akun login</span>
        </label>
      </div>
      <div id="account-fields" class="{{ old('create_account') ? '' : 'hidden' }} card-body space-y-4">
        <div>
          <label class="form-label">Email Login</label>
          <input type="email" name="user_email" value="{{ old('user_email') }}"
            class="form-input" placeholder="email@isbajaya.org">
          @error('user_email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Password Sementara</label>
            <input type="password" name="user_password" class="form-input" placeholder="••••••••">
            @error('user_password')<p class="form-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="form-label">Role Akses</label>
            <select name="user_role" class="form-select">
              <option value="member" {{ old('user_role')=='member' ? 'selected' : '' }}>Member</option>
              <option value="admin" {{ old('user_role')=='admin' ? 'selected' : '' }}>Admin</option>
              <option value="chairman" {{ old('user_role')=='chairman' ? 'selected' : '' }}>Ketua Umum</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    {{-- FORM FOOTER --}}
    <div class="flex items-center justify-end gap-3 pb-6">
      <a href="{{ route('admin.members.index') }}" class="btn-ghost border border-gray-300">
        Batal
      </a>
      <button type="submit" class="btn-primary px-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Anggota
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

function toggleAccountFields(checked) {
  document.getElementById('account-fields').classList.toggle('hidden', !checked);
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

// Load positions jika ada old value
@if(old('department_id'))
loadPositions({{ old('department_id') }});
@endif
</script>
@endpush
