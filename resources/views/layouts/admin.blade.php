<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — HRIS ISBA JAYA</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <x-vite-assets />
</head>
<body class="bg-[#F4F6F9] font-sans">

<div class="flex h-screen overflow-hidden">

  {{-- ===== SIDEBAR ===== --}}
  <aside id="sidebar"
    class="w-64 flex-shrink-0 flex flex-col h-full overflow-y-auto transition-all duration-300" style="background-color: #980D0D">

    {{-- Logo Area --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
      <div class="flex-shrink-0">
        <x-application-logo class="h-11 w-11 rounded-full bg-white p-0.5 shadow-md" />
      </div>
      <div>
        <p class="text-white font-bold text-sm leading-tight uppercase tracking-tighter">HRIS ISBA JAYA</p>
        <p class="text-red-200 text-[10px] font-medium uppercase">STT Nurul Fikri</p>
      </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1">

      {{-- Section: Menu Utama --}}
      <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Menu Utama</p>

      {{-- Dashboard --}}
      <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('chairman.dashboard') }}"
        class="sidebar-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
      </a>

      {{-- Data Anggota --}}
      @role('admin')
      <a href="{{ route('admin.members.index') }}"
        class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Data Anggota
      </a>

      {{-- Verifikasi Status --}}
      <a href="{{ route('admin.verification.index') }}"
        class="sidebar-link {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Verifikasi Status
      </a>
      @endrole

      {{-- Struktur Organisasi --}}
      <a href="{{ auth()->user()->hasRole('admin') ? route('admin.organization.index') : (auth()->user()->hasRole('chairman') ? route('chairman.organization') : route('member.organization')) }}"
        class="sidebar-link {{ request()->routeIs('*.organization*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Struktur Organisasi
      </a>

      {{-- Departemen & Jabatan --}}
      @role('admin')
      <a href="{{ route('admin.departments.index') }}"
        class="sidebar-link {{ request()->routeIs('admin.departments.*') || request()->routeIs('admin.positions.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
        </svg>
        Master Data Struktur
      </a>
      @endrole

      {{-- Laporan --}}
      <a href="{{ auth()->user()->hasRole('admin') ? route('admin.reports.index') : route('chairman.reports.index') }}"
        class="sidebar-link {{ request()->routeIs('*.reports.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Laporan
      </a>

      {{-- Profil Saya --}}
      @can('view_own_profile')
      <a href="{{ route('member.profile') }}"
        class="sidebar-link {{ request()->routeIs('member.profile') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Profil Saya
      </a>
      @endcan

      {{-- Berita Acara --}}
      @role('admin')
      <a href="{{ route('admin.events.index') }}"
        class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Berita Acara
      </a>
      
      <a href="{{ route('admin.attendance.index') }}"
        class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        Absensi Acara
      </a>
      @endrole

      {{-- Section: Pengaturan --}}
      @role('admin')
      <div class="pt-3">
        <p class="text-white/60 text-[10px] font-black uppercase tracking-widest px-3 mb-2">Pengaturan</p>
        <a href="{{ route('admin.settings.index') }}"
          class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          Pengaturan Sistem
        </a>
      </div>
      @endrole
    </nav>

    {{-- Logout --}}
    <div class="p-3 border-t border-white/10">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="sidebar-link w-full text-red-300 hover:text-red-200 hover:bg-red-900/30">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Logout
        </button>
      </form>
    </div>
  </aside>

  {{-- ===== MAIN CONTENT ===== --}}
  <div class="flex-1 flex flex-col overflow-hidden">

    {{-- TOP NAVBAR --}}
    <header class="bg-white border-b border-gray-200 px-6 py-3.5 flex items-center justify-between flex-shrink-0">
      <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-navy transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <h1 class="text-base font-semibold text-navy">@yield('page-title', 'Dashboard')</h1>
      </div>

      <div class="flex items-center gap-3">
        {{-- Notification Bell --}}
        <button class="relative p-2 text-gray-500 hover:text-navy hover:bg-gray-100 rounded-lg transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          @if(isset($pendingCount) && $pendingCount > 0)
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
          @endif
        </button>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open"
            class="flex items-center gap-2.5 pl-3 pr-2 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="text-right hidden sm:block">
              <p class="text-sm font-semibold text-navy leading-tight">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="text-sm font-medium text-navy">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                Keluar
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    {{-- Flash Messages --}}
    <div class="px-6 pt-4">
      @if(session('success'))
      <div class="mb-4 p-3.5 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">✕</button>
      </div>
      @endif

      @if(session('error'))
      <div class="mb-4 p-3.5 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
        </svg>
        <p class="text-sm text-red-700">{{ session('error') }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">✕</button>
      </div>
      @endif
    </div>

    {{-- PAGE CONTENT --}}
    <main class="flex-1 overflow-y-auto px-6 pb-6">
      @yield('content')
    </main>
  </div>
</div>

{{-- Alpine.js untuk dropdown --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('w-64');
  sidebar.classList.toggle('w-0');
  sidebar.classList.toggle('overflow-hidden');
}
</script>

{{-- Sidebar link styles --}}
<style>
.sidebar-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.7);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  width: 100%;
}
.sidebar-link:hover {
  background: rgba(255,255,255,0.1);
  color: white;
}
.sidebar-link.active {
  background: rgba(255,255,255,0.15);
  color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  border-left: 4px solid #FFD700;
  padding-left: 10px;
}
</style>

@stack('scripts')
</body>
</html>
