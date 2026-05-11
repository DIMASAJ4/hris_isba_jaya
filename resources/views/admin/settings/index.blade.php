@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan & Keamanan')

@section('content')
<div class="py-4 space-y-6">

  {{-- TABS --}}
  <div x-data="{ tab: 'users' }">
    <div class="flex border-b border-gray-200 mb-6">
      <button @click="tab='users'" 
        :class="tab==='users' ? 'border-b-2 border-primary text-primary' : 'text-gray-500'"
        class="px-6 py-3 text-sm font-medium transition-all">
        Manajemen Pengguna
      </button>
      <button @click="tab='roles'" 
        :class="tab==='roles' ? 'border-b-2 border-primary text-primary' : 'text-gray-500'"
        class="px-6 py-3 text-sm font-medium transition-all">
        Hak Akses & Role
      </button>
    </div>

    {{-- TAB: USERS --}}
    <div x-show="tab==='users'" class="space-y-5">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-navy">Daftar Pengguna Sistem</h3>
        <button onclick="openUserModal()" class="btn-primary text-sm">
          + Tambah Pengguna
        </button>
      </div>

      <div class="card">
        <div class="table-wrapper rounded-xl">
          <table class="table">
            <thead>
              <tr>
                <th class="w-12">No</th>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th class="text-center w-32">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <td class="text-gray-400 text-center">{{ $loop->iteration }}</td>
                <td>
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                  </div>
                </td>
                <td class="text-gray-600">{{ $user->email }}</td>
                <td>
                  @foreach($user->roles as $role)
                  <span class="px-2 py-0.5 bg-blue-50 text-primary text-xs rounded-md font-semibold border border-blue-100 uppercase">
                    {{ $role->name }}
                  </span>
                  @endforeach
                </td>
                <td>
                  <span class="badge-aktif">Aktif</span>
                </td>
                <td>
                  <div class="flex items-center justify-center gap-1">
                    <button onclick="openResetModal({{ $user }})" class="p-1.5 text-amber hover:bg-amber-50 rounded-lg" title="Reset Password">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-2 2a2 2 0 002 2m2-3a2 2 0 11-4 0 2 2 0 014 0zM7 10a5 5 0 015-5 5 5 0 015 5v3a5 5 0 01-5 5H7a5 5 0 01-5-5v-3a5 5 0 015-5z"/>
                      </svg>
                    </button>
                    <button onclick="openUserModal({{ $user }})" class="p-1.5 text-primary hover:bg-blue-50 rounded-lg" title="Edit">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <form action="{{ route('admin.settings.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                      @csrf @method('DELETE')
                      <button class="p-1.5 text-danger hover:bg-red-50 rounded-lg" title="Hapus">
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

    {{-- TAB: ROLES & PERMISSIONS --}}
    <div x-show="tab==='roles'" class="space-y-5">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-navy">Matriks Hak Akses (RBAC)</h3>
        <p class="text-sm text-gray-500 italic">Sesuaikan izin akses untuk setiap jabatan utama.</p>
      </div>

      <div class="card overflow-hidden">
        <form action="{{ route('admin.settings.roles.update') }}" method="POST">
          @csrf
          <div class="table-wrapper rounded-none border-0">
            <table class="table">
              <thead>
                <tr class="bg-gray-50">
                  <th class="text-navy">Fitur / Modul</th>
                  @foreach($roles as $role)
                  <th class="text-center text-navy">{{ strtoupper($role->name) }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($permissions->groupBy(fn($p) => explode('-', $p->name)[1] ?? 'Lainnya') as $module => $modulePermissions)
                <tr class="bg-blue-50/30">
                  <td colspan="{{ $roles->count() + 1 }}" class="font-bold text-primary text-xs uppercase tracking-widest px-4 py-2">
                    Modul: {{ $module }}
                  </td>
                </tr>
                @foreach($modulePermissions as $permission)
                <tr>
                  <td class="pl-8">
                    <span class="text-sm text-gray-700">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</span>
                  </td>
                  @foreach($roles as $role)
                  <td class="text-center">
                    <input type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $permission->id }}"
                      {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                      class="rounded text-primary focus:ring-primary/30">
                  </td>
                  @endforeach
                </tr>
                @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="submit" class="btn-primary">Simpan Konfigurasi Akses</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- MODAL: USER FORM --}}
<div id="modal-user" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
      <h3 id="user-modal-title" class="font-semibold text-navy text-lg">Tambah Pengguna</h3>
      <button onclick="closeModal('modal-user')" class="text-gray-400 hover:text-gray-600">✕</button>
    </div>
    <form id="user-form" method="POST">
      @csrf
      <div id="user-method-container"></div>
      <div class="p-6 space-y-4">
        <div>
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" id="user-name" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Email</label>
          <input type="email" name="email" id="user-email" class="form-input" required>
        </div>
        <div id="password-field">
          <label class="form-label">Password Sementara</label>
          <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter">
        </div>
        <div>
          <label class="form-label">Role Akses</label>
          <select name="role" id="user-role" class="form-select">
            @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="p-6 pt-0 flex gap-3">
        <button type="button" onclick="closeModal('modal-user')" class="btn-ghost border border-gray-300 flex-1">Batal</button>
        <button type="submit" class="btn-primary flex-1">Simpan User</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL: RESET PASSWORD --}}
<div id="modal-reset" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
    <div class="p-6 border-b border-gray-100">
      <h3 class="font-semibold text-navy text-lg">Reset Password</h3>
      <p class="text-sm text-gray-500 mt-1">Ganti password untuk: <span id="reset-user-name" class="font-bold text-navy"></span></p>
    </div>
    <form id="reset-form" method="POST">
      @csrf
      <div class="p-6 space-y-4">
        <div>
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>
      </div>
      <div class="p-6 pt-0 flex gap-3">
        <button type="button" onclick="closeModal('modal-reset')" class="btn-ghost border border-gray-300 flex-1">Batal</button>
        <button type="submit" class="btn-primary flex-1">Perbarui Password</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function openUserModal(user = null) {
    const modal = document.getElementById('modal-user');
    const form = document.getElementById('user-form');
    const title = document.getElementById('user-modal-title');
    const methodContainer = document.getElementById('user-method-container');
    const pwdField = document.getElementById('password-field');
    
    if (user) {
      title.textContent = 'Edit Pengguna';
      form.action = `/admin/settings/users/${user.id}`;
      methodContainer.innerHTML = '@method("PUT")';
      document.getElementById('user-name').value = user.name;
      document.getElementById('user-email').value = user.email;
      document.getElementById('user-role').value = user.roles[0]?.name || 'member';
      pwdField.classList.add('hidden');
    } else {
      title.textContent = 'Tambah Pengguna';
      form.action = "{{ route('admin.settings.users.store') }}";
      methodContainer.innerHTML = '';
      form.reset();
      pwdField.classList.remove('hidden');
    }
    modal.classList.remove('hidden');
  }

  function openResetModal(user) {
    document.getElementById('reset-user-name').textContent = user.name;
    document.getElementById('reset-form').action = `/admin/settings/users/${user.id}/reset-password`;
    document.getElementById('modal-reset').classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>
@endpush
