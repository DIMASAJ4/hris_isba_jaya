@extends('layouts.auth')
@section('title', 'Masuk')
@section('content')

<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex min-h-[520px]">

  {{-- LEFT PANEL --}}
  <div class="hidden md:flex w-5/12 flex-col justify-between p-10" style="background-color: #980D0D">
    {{-- Logo --}}
    <div>
      <div class="mb-8 flex justify-start">
        <x-application-logo class="h-24 w-24 rounded-full bg-white p-1 shadow-lg border border-white/20" />
      </div>
      <h1 class="text-2xl font-bold text-white mb-2">HRIS ISBA JAYA</h1>
      <p class="text-red-200 text-sm italic">Kelola Anggota, Bangun Organisasi</p>
    </div>

    {{-- Illustration --}}
    <div class="my-8">
      <div class="bg-white/5 rounded-xl p-6 border border-white/10">
        <div class="grid grid-cols-3 gap-2 mb-4">
          @foreach(range(1,9) as $i)
          <div class="h-8 bg-white/10 rounded {{ $i % 3 === 0 ? 'bg-primary/30' : '' }}"></div>
          @endforeach
        </div>
        <div class="h-2 bg-white/10 rounded mb-2"></div>
        <div class="h-2 bg-primary/40 rounded w-3/4"></div>
      </div>
    </div>

    {{-- Footer --}}
    <p class="text-blue-400 text-xs">© 2026 ISBA JAYA · STT Nurul Fikri</p>
  </div>

  {{-- RIGHT PANEL --}}
  <div class="flex-1 flex flex-col justify-center px-8 md:px-12 py-10">
    <div class="mb-8">
      <h2 class="text-2xl font-bold mb-1" style="color: #980D0D">Selamat Datang</h2>
      <p class="text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
    <div class="mb-4 p-3.5 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
      <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
      </svg>
      <p class="text-sm text-red-700">{{ $errors->first() }}</p>
    </div>
    @endif

    {{-- Success flash --}}
    @if(session('success'))
    <div class="mb-4 p-3.5 bg-green-50 border border-green-200 rounded-lg">
      <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      {{-- Email --}}
      <div>
        <label class="form-label" for="email">Email</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
            </svg>
          </div>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
            class="form-input pl-10 @error('email') border-red-400 @enderror"
            placeholder="nama@organisasi.com" required autofocus>
        </div>
        @error('email')<p class="form-error">{{ $message }}</p>@enderror
      </div>

      {{-- Password --}}
      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label class="form-label mb-0" for="password">Password</label>
          <a href="#" class="text-xs text-primary hover:underline">Lupa password?</a>
        </div>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <input id="password" type="password" name="password"
            class="form-input pl-10 pr-10 @error('password') border-red-400 @enderror"
            placeholder="••••••••" required>
          <button type="button" onclick="togglePassword()"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
        @error('password')<p class="form-error">{{ $message }}</p>@enderror
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full py-3 text-white font-semibold rounded-lg hover:opacity-90
               transition-all duration-200 flex items-center justify-center gap-2 mt-2" style="background-color: #980D0D">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
        </svg>
        Masuk
      </button>
    </form>

    <p class="text-center text-xs text-gray-400 mt-8 uppercase tracking-wider">
      Sistem hanya untuk pengurus ISBA JAYA
    </p>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
